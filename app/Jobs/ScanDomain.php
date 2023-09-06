<?php

namespace App\Jobs;

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

    private mixed $domain;
    private mixed $scan_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($domain, $scan_id)
    {
        $this->domain = $domain;
        $this->scan_id = $scan_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OwaspZapService $owaspZapService)
    {
        $this->storeAlerts($owaspZapService, $this->domain, $this->scan_id);
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Failed Running Job: ' . $exception->getMessage());
    }

    public function storeAlerts($service, $domain_url, $scan_id)
    {
        $start = 0;
        $count = 1000;  // Number of records to fetch in a single API call
        $scan_status = 0;
        $scan = \App\Models\Scan::query()->where('scan_id', $scan_id)->get()->first();

        while (true) {
            $alerts = $service->getAlerts($domain_url, $start, $count);
            $alerts = $alerts['alerts'];
            if (empty($alerts)) {
                break;  // Exit the loop if no more alerts are returned
            }
            $bulkInsertData = [];
            foreach ($alerts as $alert) {
                try {
                    $bulkInsertData[] = [
                        'scan_id' => $scan_id,
                        'sourceid' => $alert['sourceid'],
                        'alertRef' => $alert['alertRef'],
                        'a_id' => $alert['id'],
                        'created_at' => now(),
//                        'other' => $alert['other'],
//                        'method' => $alert['method'],
//                        'evidence' => $alert['evidence'],
//                        'pluginId' => $alert['pluginId'],
//                        'cweid' => $alert['cweid'],
//                        'confidence' => $alert['confidence'],
//                        'wascid' => $alert['wascid'],
//                        'description' => $alert['description'],
//                        'messageId' => $alert['messageId'],
//                        'inputVector' => $alert['inputVector'],
//                        'url' => $alert['url'],
//                        'tags' => json_encode($alert['tags']),
//                        'reference' => $alert['reference'],
//                        'solution' => $alert['solution'],
//                        'alert' => $alert['alert'],
//                        'param' => $alert['param'],
//                        'attack' => $alert['attack'],
//                        'name' => $alert['name'],
//                        'risk' => $alert['risk'],
//                        'updated_at' => now()
                    ];
                } catch (\Exception $e) {
                    Log::error('Failed to prepare alert for bulk insert: ' . $e->getMessage());
                }
            }
            try {
                ScanAlerts::insert($bulkInsertData);
            } catch (\Exception $e) {
                Log::error('Failed to perform bulk insert: ' . $e->getMessage());
            }
            if ($scan_status != 100) {
                $status = $service->getScanStatus($this->scan_id);
                $scan_status = $status['status'];
                $scan->scan_status = $scan_status;
                $scan->save();
            }
            $start += $count;
        }
    }
}
