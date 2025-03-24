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

class PollScanStatus implements ShouldQueue
{
    public $timeout = 60;
    public $tries = 3;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $domain;
    protected Scan $scan;
    const DELAY_SECONDS = 15;
    const BATCH_COUNT = 500;

    public function __construct(string $domain, Scan $scan)
    {
        $this->domain = $domain;
        $this->scan = $scan;
    }

    public function handle(OwaspZapService $owaspZapService)
    {
        try {
            $statusResponse = $owaspZapService->getScanStatus($this->scan->scan_id);
            $status = isset($statusResponse['status']) ? (int)$statusResponse['status'] : 0;

            if ($status < 100) {
                $this->scan->update(['scan_status' => 'Scanning (' . $status . '%)']);
                self::dispatch($this->domain, $this->scan)
                    ->delay(now()->addSeconds(self::DELAY_SECONDS));
            } else {
                $this->scan->update(['scan_status' => 'Initialized Alerts']);
                $this->dispatchAlertBatchJobs($owaspZapService);
                $this->scan->update(['scan_status' => 'Processing Alerts']);
            }
        } catch (Throwable $exception) {
            Log::error("Error in PollScanStatus job for scan {$this->scan->scan_id}: " . $exception->getMessage());
            throw $exception;
        }
    }

    protected function dispatchAlertBatchJobs(OwaspZapService $service)
    {
        // NOTE: Remove the +1 adjustment unless it is required by your API.
        $alertsCount = $service->getAlertsCount($this->domain);
        $expectedAlerts = isset($alertsCount['numberOfAlerts']) ? $alertsCount['numberOfAlerts'] : 0;
        $this->scan->update(['expected_alerts' => $expectedAlerts]);

        $batches = (int) ceil($expectedAlerts / self::BATCH_COUNT);
        for ($i = 0; $i < $batches; $i++) {
            $offset = $i * self::BATCH_COUNT;
            ProcessAlertsBatch::dispatch($this->domain, $this->scan->scan_id, $offset, self::BATCH_COUNT);
        }
    }
}
