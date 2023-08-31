<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScanAlerts extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function scan()
    {
        return $this->belongsTo(Scan::class);
    }

    public function owasp_value()
    {
        return $this->belongsTo(OwaspZapCoreValue::class, 'alertRef', 'alert_ref_id');
    }

}
