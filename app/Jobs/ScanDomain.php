<?php
namespace App\Jobs;

use App\Models\Scan;
use App\Jobs\ProcessAlertsBatch;
use App\Services\OwaspZapService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ScanDomain implements ShouldQueue
{
    public $timeout = 1200; // 20 minutes
    public $tries = 3;

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
                $this->dispatchAlertBatchJobs($owaspZapService, $this->domain, $this->scan->scan_id);
                $this->updateStatus($this->scan, 'Processing Alerts');
            }
        } catch (Throwable $exception) {
            Log::error('Exception in ScanDomain Job: ' . $exception->getMessage());
            throw $exception;
        }
    }

    protected function dispatchAlertBatchJobs(OwaspZapService $service, $domain_url, $scan_id)
    {
        $alerts_count = $service->getAlertsCount($domain_url);
        Scan::query()->where('scan_id', $scan_id)->update(['expected_alerts' => $alerts_count['numberOfAlerts'] + 1]);
        $total_count = $alerts_count['numberOfAlerts'] + 1;
        $batches = (int) ceil($total_count / self::BATCH_COUNT);
        for ($i = 0; $i < $batches; $i++) {
            $offset = $i * self::BATCH_COUNT;
            ProcessAlertsBatch::dispatch($domain_url, $scan_id, $offset, self::BATCH_COUNT);
        }
    }

    private function checkStatusFromAPI($service, $scan_id)
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
