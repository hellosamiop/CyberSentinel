<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\PollScanStatus;
use App\Models\Domain;
use App\Models\Scan;
use App\Models\ScanAlerts;
use App\Models\ScanResult;
use App\Services\OwaspZapService;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        $domain = Domain::query()->find($request->domain_id);
        $service = new OwaspZapService();
        $scan = $service->startScan($domain->domain_url);

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
                        'result' => json_encode($result),
                        'score' => 0,
                    ]);
                }
                DB::commit();
                $flag = 'b';
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->route('scans.index')
                    ->with(['message' => 'Domain Scan Failed. Could not save scan. ' . $e->getMessage(), 'message_type' => 'danger']);
            }
            PollScanStatus::dispatch($domain->domain_url, $new_scan);
        }
        if ($flag == 'b') {
            return redirect()->route('scans.index')
                ->with(['message' => 'Domain Scan initiated.', 'message_type' => 'success']);
        } else {
            return redirect()->route('scans.index')
                ->with(['message' => 'Domain Scan Failed. Could not retrieve scan id', 'message_type' => 'danger']);
        }
    }

    public function status()
    {
        $scans = Auth::user()->scans()->get();
        $statuses = [];

        foreach ($scans as $scan) {
            $statuses[$scan->id] = $scan->scan_status;
        }

        return response()->json($statuses);
    }

    public function destroy($scan_id)
    {
        $scan = Scan::query()->find($scan_id);
        $scan->scanResults()->delete();
        $scan->scanAlerts()->delete();
        $scan->delete();
        return redirect()->route('scans.index')
            ->with(['message' => 'Scan deleted successfully.', 'message_type' => 'success']);
    }

    public function getReport($id)
    {
        $scan = Scan::find($id);
        $domain = $scan->domain;

        if ($scan) {
            $health_data = generateHealthData($scan->scan_id);
            $attack_data = generateAttackData($scan->scan_id);
            $alerts = ScanAlerts::query()->where('scan_id', $scan->id)->get();
        } else {
            $health_data = generateHealthData(null);
            $attack_data = generateAttackData(null);
            $alerts = collect(); // Empty collection if no scan exists
        }

        $data = [
            'health_data' => $health_data,
            'attack_data' => $attack_data,
        ];


        $viewData = [
            'domain' => $domain,
            'data' => $data,
            'alerts' => $alerts,
        ];

        $pdf = Pdf::loadView('reports.scan_report', $viewData);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('report_' . Str::slug($domain->name) . '.pdf');
    }

}
