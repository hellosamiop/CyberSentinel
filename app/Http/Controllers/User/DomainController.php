<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Domain;
use App\Models\Industry;
use App\Services\OwaspZapService;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function index()
    {
        $domains = Domain::with('industry')->get();
        return view('user.domains.index', compact('domains'));
    }
    public function create()
    {
        $industries = Industry::all();
        $countries = Country::all();
        return view('user.domains.create', compact('industries', 'countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'domain_url' => 'required|url',
            'industry_id' => 'required|exists:industries,id',
            'country_id' => 'required|exists:countries,id',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $domain = new Domain();
        $domain->name = $request->name;
        $domain->domain_url = $request->domain_url;
        $domain->industry_id = $request->industry_id;
        $domain->country_id = $request->country_id;
        $domain->user_id = auth()->user()->id;
        $domain->save();

        //save logo
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = $domain->id . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/logos', $filename);
            $domain->logo_url = $filename;
            $domain->save();
        }

        return redirect()->route('domains.index')
        ->with(['message' => 'Domain created successfully.', 'message_type' => 'success']);
    }

    public function show(Domain $domain)
    {
        return view('user.domains.show', compact('domain'));
    }

    public function edit(Domain $domain)
    {
        $industries = Industry::all();
        $countries = Country::all();
        return view('user.domains.edit', compact('domain', 'industries', 'countries'));
    }

    public function update(Request $request, Domain $domain)
    {
        $request->validate([
            'name' => 'required',
            'domain_url' => 'required|url',
            'industry_id' => 'required|exists:industries,id',
            'country_id' => 'required|exists:countries,id',
        ]);

        $domain->name = $request->name;
        $domain->industry_id = $request->industry_id;
        $domain->domain_url = $request->domain_url;
        $domain->user_id = auth()->user()->id;
        $domain->country_id = $request->country_id;
        $domain->save();

        //save logo
        if ($request->hasFile('logo')) {
//            delete existing logo
            if ($domain->logo_url) {
                $path = storage_path('app/public/logos/' . $domain->logo_url);
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            $file = $request->file('logo');
            $filename = $domain->id . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/logos', $filename);
            $domain->logo_url = $filename;
            $domain->save();
        }

        return redirect()->route('domains.index')
            ->with(['message' => 'Domain updated successfully.', 'message_type' => 'success']);
    }

    public function destroy(Domain $domain)
    {
        $domain->delete();
        return response()->json(['success' => 'Domain deleted successfully.']);
    }

    public function showStatus(Domain $domain, OwaspZapService $zapService, $scanId)
    {
        $status = $zapService->getScanStatus($scanId);
        // Handle the status...

        $results = $zapService->getScanResults($scanId);
        // Handle the results...

        $alerts = $zapService->getAlerts($domain->domain_url);
        // Handle the alerts...

    }
}
