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
            $scans = [$this->scans()->latest()->pluck('id')->first()];
        } else {
            $scans = $this->scans()->latest()->pluck('id')->toArray();
        }
        $scores = [];
        foreach ($scans as $scan) {
            $score = ScanResult::query()
                ->where('scan_id', $scan)
                ->whereNotNull('score')
                ->orderBy('created_at', 'desc')
                ->pluck('score')
                ->first();
            if ($score) {
                $scores[] = $score;
            }
        }
        return $scores;
    }

    public function getLatestScoreAttribute()
    {
        return $this->scoreHistory(true)[0] ?? 0;
    }
}
