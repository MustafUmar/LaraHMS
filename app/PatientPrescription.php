<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientPrescription extends Model
{
    protected $fillable = ['status'];

    public function patrecord()
    {
        return $this->belongsTo('App\PatientRecord', 'patrec_id');
    }

    public function payment()
    {
        return $this->belongsTo('App\Payment', 'payment_id');
    }

    public function meds()
    {
        return $this->hasMany('App\MedPrescription', 'presc_id');
    }
}
