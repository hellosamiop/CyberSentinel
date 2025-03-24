<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScanResult extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scan()
    {
        return $this->belongsTo(Scan::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = now();

            if (!isset($model->score)) {
                $model->score = generateHistoricalScore($model->scan_id);
            }
        });
    }
}
