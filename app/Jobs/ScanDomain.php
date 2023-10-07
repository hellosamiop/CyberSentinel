<?php

namespace App\Jobs;

use App\Models\Scan;
use App\Models\ScanAlerts;
use App\Services\OwaspZapService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ScanDomain implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $domain;
    private Scan $scan;

    const BATCH_COUNT = 500;
    const DELAY_SECONDS = 15;

    public function __construct($domain, Scan $scan)
    {
        $this->domain = $domain;
        $this->scan = $scan;
    }

    public function handle(OwaspZapService $owaspZapService)
    {
        try {
            $status = $this->checkStatusFromAPI($owaspZapService, $this->scan->scan_id);
            if ($status < 100) {
                $this->updateStatus($this->scan, 'Scanning (' . $status . ')');
                $this->release(self::DELAY_SECONDS);
            } else {
                $this->updateStatus($this->scan, 'Initialized Alerts');
                $this->storeAlerts($owaspZapService, $this->domain, $this->scan->scan_id);
            }
        } catch (Throwable $exception) {
            Log::error('Exception in ScanDomain Job: ' . $exception->getMessage());
            throw $exception;
        }
    }

    public function failed(Throwable $exception): void
    {
        $this->updateStatus($this->scan, 'Failed: Error Code-' . $exception->getCode());
        Log::error('Failed Running Job: ' . $exception->getMessage());
    }

    public function storeAlerts($service, $domain_url, $scan_id)
    {
        $start = 0;
        $scan = \App\Models\Scan::query()->where('scan_id', $scan_id)->get()->first();
        $alerts_count = $service->getAlertsCount($domain_url);
        $total_count = $alerts_count['numberOfAlerts'] + 1;
        while ($start < $total_count) {
            $alerts = $service->getAlerts($domain_url, $start, self::BATCH_COUNT);
            $alerts = $alerts['alerts'];
            if (empty($alerts)) {
                break;  // Exit the loop if no more alerts are returned
            }
            $bulkInsertData = [];
            foreach ($alerts as $alert) {
                $bulkInsertData[] = [
                    'scan_id' => $scan_id,
                    'sourceid' => $alert['sourceid'],
                    'alertRef' => $alert['alertRef'],
                    'a_id' => $alert['id'],
                    'created_at' => now(),
                ];
                $start++;
            }
            ScanAlerts::insert($bulkInsertData);
            $this->updateStatus($this->scan, 'Fetching Alerts (' . $start . '/' . $total_count . ')');
        }
        if ($start >= $total_count) {
            $this->updateStatus($this->scan, 'Completed');
        }
    }

    private function checkStatusFromAPI($service, mixed $scan_id)
    {
        $status = $service->getScanStatus($scan_id);
        return $status['status'];
    }


    private function updateStatus($scan, $status)
    {
        $scan->scan_status = $status;
        $scan->save();
    }
}
