<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Domain extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function getScoreAttribute($value)
    {
        if ($this->scans()->count() == 0) {
            return 'Not Scanned';
        } else {
            $score = $this->scoreHistory(true)[0];
            $this->score = $score;
            $this->save();
        }
        return $score;
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


    public function scoreHistory($latest = false): array
    {
        if ($latest) {
            $scans = [$this->scans()->latest()->pluck('scan_id')->first()];
        } else {
            $scans = $this->scans()->latest()->pluck('scan_id')->toArray();
        }
        $scores = DB::table('scans')
            ->leftJoin('scan_alerts', 'scans.scan_id', '=', 'scan_alerts.scan_id')
            ->leftJoin('owasp_zap_core_values', 'scan_alerts.alertRef', '=', 'owasp_zap_core_values.id') // Replace with actual foreign key
            ->select('scans.scan_id', DB::raw('SUM(owasp_zap_core_values.detectability + owasp_zap_core_values.exploitability + owasp_zap_core_values.technical_impact) as total_score'))
            ->whereIn('scans.scan_id', $scans)
            ->groupBy('scans.scan_id')
            ->pluck('total_score')->toArray();
        return array_map(function ($value) {
            return $value === null ? 0 : $value;
        }, $scores);
    }
}
