<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientRecord extends Model
{
    protected $fillable = ['pat_purpose','status','is_diagnosed','lab_req','sessioned','is_presc'];

    public function patient()
    {
        return $this->belongsTo('App\Patient', 'patid');
    }

    public function patdoctor()
    {
        return $this->hasMany('App\PatientDoctor', 'patrec_id');
    }

    public function patservice()
    {
        return $this->hasMany('App\PatientService', 'patrec_id');
    }

    public function patpresc()
    {
        return $this->hasMany('App\PatientPrescription', 'patrec_id');
    }

    public function payment()
    {
        return $this->hasMany('App\Payment', 'patrec_id');
    }

}
