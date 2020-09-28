<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedPrescription extends Model
{
    protected $fillable = ['qty', 'unit', 'type', 'amount', 'status'];

    public function presc()
    {
        return $this->belongsTo('App\PatientPrescription', 'presc_id');
    }

    public function pharm()
    {
        return $this->belongsTo('App\Pharmacy', 'pharm_id');
    }
}
