<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function getScoreAttribute($value)
    {
        if ($value) {
            return $value;
        }else{
            $scan = $this->scans()->latest()->first();
            if ($scan) {
                $alerts = $scan->ScanAlerts;
                $score = 0;
                foreach ($alerts as $alert) {
                    $owasp_value = $alert->owasp_value;
                    $score += $owasp_value->detectability + $owasp_value->exploitability + $owasp_value->technical_impact;
                }
                $this->score = $score;
                $this->save();
                return $score;
            }
        }
        return 'Not Scanned';
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getLogoAttribute()
    {
        return $this->logo_url
            ? asset('storage/logos/' . $this->logo_url)
            : asset('assets/images/default-logo.png');
    }

    public function scans()
    {
        return $this->hasMany(Scan::class);
    }

    public function getReportData()
    {
        $scan = $this->scans()->latest()->first();
        if ($scan) {
            $alerts = $scan->ScanAlerts;
            foreach ($alerts as $alert) {
                dd($alert->owasp_value);
            }
        }
    }

    public function scoreHistory(){
        $scores = [];
        $scans = $this->scans()->latest()->get();
        foreach ($scans as $scan){
            $alerts = $scan->ScanAlerts;
            $score = 0;
            foreach ($alerts as $alert) {
                $owasp_value = $alert->owasp_value;
                $score += $owasp_value->detectability + $owasp_value->exploitability + $owasp_value->technical_impact;
            }
            $scores[] = $score;
        }
        return $scores;
    }
}
