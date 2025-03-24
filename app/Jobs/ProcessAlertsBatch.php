<?php

namespace App\Jobs;

use App\Models\Scan;
use App\Models\ScanAlerts;
use App\Models\ScanResult;
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
            // Retrieve alerts from the API for this batch
            $alertsResponse = $service->getAlerts($this->domain_url, $this->offset, $this->batchCount);
            $alerts = $alertsResponse['alerts'] ?? [];

            if (!empty($alerts)) {
                $bulkInsertData = [];
                foreach ($alerts as $alert) {
                    $bulkInsertData[] = [
                        'scan_id'    => $this->scan_id,
                        'sourceid'   => $alert['sourceid'],
                        'alertRef'   => $alert['alertRef'],
                        'a_id'       => $alert['id'],
                        'created_at' => now(),
                    ];
                }
                ScanAlerts::insert($bulkInsertData);
                Log::info("Inserted batch of alerts for scan_id {$this->scan_id} from offset {$this->offset}");
            } else {
                // If the API returns an empty batch, assume there are no more alerts
                Log::info("No alerts returned for scan_id {$this->scan_id} at offset {$this->offset}");
                // Optionally update expected_alerts to the current processed count so finalization can occur
                $currentCount = ScanAlerts::where('scan_id', $this->scan_id)->count();
                $scan = Scan::where('scan_id', $this->scan_id)->first();
                if ($scan && $scan->expected_alerts > $currentCount) {
                    $scan->update(['expected_alerts' => $currentCount]);
                }
            }

            // Update progress: count alerts processed and update scan status accordingly
            $processedCount = ScanAlerts::where('scan_id', $this->scan_id)->count();
            $scan = Scan::where('scan_id', $this->scan_id)->first();

            if ($scan) {
                Log::info("Scan {$this->scan_id}: Processed {$processedCount} alerts out of expected {$scan->expected_alerts}");

                if ($processedCount < $scan->expected_alerts) {
                    $scan->update([
                        'scan_status' => "Processing Alerts: {$processedCount}/{$scan->expected_alerts}"
                    ]);
                } else {
                    // Once all alerts are processed, create or update the ScanResult.
                    $scanResult = ScanResult::where('scan_id', $scan->id)->first();
                    if (!$scanResult) {
                        // The score will be calculated automatically via the model's creating event.
                        ScanResult::create([
                            'scan_id' => $scan->id,
                            'result'  => '{}'
                        ]);
                        Log::info("Created ScanResult for scan_id {$this->scan_id}");
                    }
                    $scan->update([
                        'scan_status' => 'Scan Completed'
                    ]);
                }
            }
        } catch (Throwable $exception) {
            Log::error('Exception in ProcessAlertsBatch Job for scan_id ' . $this->scan_id . ': ' . $exception->getMessage());
            throw $exception;
        }
    }
}
