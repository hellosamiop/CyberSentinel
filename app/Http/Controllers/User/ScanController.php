<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\ScanDomain;
use App\Models\Domain;
use App\Models\Scan;
use App\Models\ScanAlerts;
use App\Models\ScanResult;
use App\Services\OwaspZapService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScanController extends Controller
{
    public function index()
    {
        $scans = auth()->user()->scans;
        return view('user.scans.index', compact('scans'));
    }

    public function create()
    {
        $domains = auth()->user()->domains;
        return view('user.scans.create', compact('domains'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'domain_id' => 'required|exists:domains,id',
        ]);
        if (auth()->user()->available_tokens <= 0) {
            return redirect()->route('scans.index')
                ->with(['message' => 'Insufficient Tokens for scan.', 'message_type' => 'danger']);
        }

        $domain = Domain::query()->find($request->domain_id);
        $service = new OwaspZapService();
        $scan = $service->startScan($domain->domain_url);
//        $scan = $service->getAlertsCount($domain->domain_url);
//        dd($scan);

        $flag = 'a';
        if ($scan && isset($scan['scan'])) {
            $scan_id = $scan['scan'];
            DB::beginTransaction();
            try {
                $new_scan = new Scan();
                $new_scan->scan_id = $scan_id;
                $new_scan->domain_id = $request->domain_id;
                $new_scan->scan_status = 'initialized';
                $new_scan->user_id = auth()->user()->id;
                $new_scan->save();
                auth()->user()->useTokens(1);
                $result = $service->getScanResults($scan_id);
                if ($result) {
                    ScanResult::create([
                        'scan_id' => $new_scan->id,
                        'result' => json_encode($result)
                    ]);
                }
                DB::commit();
                $flag = 'b';
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('scans.index')
                    ->with(['message' => 'Domain Scan Failed. Could not save scan. ' . $e->getMessage(), 'message_type' => 'danger']);
            }
            ScanDomain::dispatch($domain->domain_url, $new_scan);
        }
        if ($flag == 'b') {
            return redirect()->route('scans.index')
                ->with(['message' => 'Domain Scanned initiated.', 'message_type' => 'success']);
        } else {
            return redirect()->route('scans.index')
                ->with(['message' => 'Domain Scan Failed. Could not retrieve scan id', 'message_type' => 'danger']);
        }
    }

    public function storeAlerts($service, $domain_url, $scan_id)
    {
        $alerts = $service->getAlerts($domain_url, 0, 10);
        $alerts = $alerts['alerts'];
        foreach ($alerts as $alert) {
            $scan_alert = new ScanAlerts();
            try {
                $scan_alert->scan_id = $scan_id;
                $scan_alert->sourceid = $alert['sourceid'];
                $scan_alert->alertRef = $alert['alertRef'];
                $scan_alert->a_id = $alert['id'];
                $scan_alert->other = $alert['other'];
                $scan_alert->method = $alert['method'];
                $scan_alert->evidence = $alert['evidence'];
                $scan_alert->pluginId = $alert['pluginId'];
                $scan_alert->cweid = $alert['cweid'];
                $scan_alert->confidence = $alert['confidence'];
                $scan_alert->wascid = $alert['wascid'];
                $scan_alert->description = $alert['description'];
                $scan_alert->messageId = $alert['messageId'];
                $scan_alert->inputVector = $alert['inputVector'];
                $scan_alert->url = $alert['url'];
                $scan_alert->tags = json_encode($alert['tags']);
                $scan_alert->reference = $alert['reference'];
                $scan_alert->solution = $alert['solution'];
                $scan_alert->alert = $alert['alert'];
                $scan_alert->param = $alert['param'];
                $scan_alert->attack = $alert['attack'];
                $scan_alert->name = $alert['name'];
                $scan_alert->risk = $alert['risk'];
                $scan_alert->save();
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }
    }

}
