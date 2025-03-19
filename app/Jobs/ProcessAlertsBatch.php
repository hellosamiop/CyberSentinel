<?php
namespace App\Jobs;

use App\Models\Scan;
use App\Models\ScanAlerts;
use App\Services\OwaspZapService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessAlertsBatch implements ShouldQueue
{
    public $timeout = 300;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $domain_url;
    protected $scan_id;
    protected $offset;
    protected $batchCount;

    public function __construct($domain_url, $scan_id, $offset, $batchCount)
    {
        $this->domain_url = $domain_url;
        $this->scan_id = $scan_id;
        $this->offset = $offset;
        $this->batchCount = $batchCount;
    }

    public function handle(OwaspZapService $service)
{
    try {
        $alertsResponse = $service->getAlerts($this->domain_url, $this->offset, $this->batchCount);
        $alerts = $alertsResponse['alerts'] ?? [];
        if (!empty($alerts)) {
            $bulkInsertData = [];
            foreach ($alerts as $alert) {
                $bulkInsertData[] = [
                    'scan_id'   => $this->scan_id,
                    'sourceid'  => $alert['sourceid'],
                    'alertRef'  => $alert['alertRef'],
                    'a_id'      => $alert['id'],
                    'created_at'=> now(),
                ];
            }
            ScanAlerts::insert($bulkInsertData);
        }

        $processedCount = ScanAlerts::where('scan_id', $this->scan_id)->count();
        $scan = Scan::query()->where('scan_id', $this->scan_id)->first();
        if ($processedCount >= $scan->expected_alerts) {
            $scan->scan_status = 'Scan Completed';
            $scan->save();
        }
    } catch (Throwable $exception) {
        Log::error('Exception in ProcessAlertsBatch Job: ' . $exception->getMessage());
        throw $exception;
    }
}

}
