<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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
//        if(auth()->user()->available_tokens <= 0){
//            return redirect()->route('scans.index')
//            ->with(['message' => 'Insufficient Tokens for scan.', 'message_type' => 'danger']);
//        }
        if(auth()->user()->scans()->where('domain_id', $request->domain_id)->exists()){
            return redirect()->route('scans.index')
            ->with(['message' => 'Domain already scanned.', 'message_type' => 'danger']);
        }

        $domain = Domain::query()->find($request->domain_id);
        $service = new OwaspZapService();
        $scan = $service->startScan($domain->domain_url);
        $flag = 'a';
        if($scan && isset($scan['scan'])) {
            $scan_id = $scan['scan'];
            $status = $service->getScanStatus($scan_id);
            $flag = 'b';
            if($status && isset($status['status'])){
                DB::beginTransaction();
                try {
                    $scan = new Scan();
                    $scan->scan_id = $scan_id;
                    $scan->domain_id = $request->domain_id;
                    $scan->scan_status = $status['status'];
                    $scan->user_id = auth()->user()->id;
                    $scan->save();
                    auth()->user()->useTokens(1);
                    $result = $service->getScanResults($scan_id);
                    if($result){
                        ScanResult::create([
                            'scan_id' => $scan->id,
                            'result' => json_encode($result)
                        ]);
                    }
                    $alerts = $service->getAlerts($domain->domain_url, 0, 5000);
                    if($alerts){
                        $this->storeAlerts($alerts, $scan->id);
                    }
                    DB::commit();
                }catch (\Exception $e){
                    DB::rollBack();
                    return redirect()->route('scans.index')
                    ->with(['message' => 'Domain Scan Failed. Could not save scan. '. $e->getMessage(), 'message_type' => 'danger']);
                }
                $flag = 'c';
            }
        }
        if ($flag == 'c') {
            return redirect()->route('scans.index')
            ->with(['message' => 'Domain Scanned successfully.', 'message_type' => 'success']);
        }
        elseif ($flag == 'b') {
            return redirect()->route('scans.index')
            ->with(['message' => 'Domain Scan Failed. Could not retrieve status', 'message_type' => 'danger']);
        }
        else {
            return redirect()->route('scans.index')
            ->with(['message' => 'Domain Scan Failed. Could not retrieve scan id', 'message_type' => 'danger']);
        }
    }

    public function storeAlerts($alerts, $scan_id){
        foreach($alerts as $alert){
            ScanAlerts::create([
                'scan_id' => $scan_id,
                'sourceid' => $alert['sourceid'],
                'alertRef' => $alert['alertRef'],
                'a_id' => $alert['id'],
                'other' => $alert['other'],
                'method' => $alert['method'],
                'evidence' => $alert['evidence'],
                'pluginId' => $alert['pluginId'],
                'cweid' => $alert['cweid'],
                'confidence' => $alert['confidence'],
                'wascid' => $alert['wascid'],
                'description' => $alert['description'],
                'messageId' => $alert['messageId'],
                'inputVector' => $alert['inputVector'],
                'url' => $alert['url'],
                'tags' => json_encode($alert['tags']),
                'reference' => $alert['reference'],
                'solution' => $alert['solution'],
                'alert' => $alert['alert'],
                'param' => $alert['param'],
                'attack' => $alert['attack'],
                'name' => $alert['name'],
                'risk' => $alert['risk'],
            ]);
        }
    }

}
