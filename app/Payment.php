<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;

class Payment extends Model
{
    protected $fillable = ['type', 'paid', 'status'];

    protected $appends = ['hashid'];

    public function patrecord()
    {
        return $this->belongsTo('App\PatientRecord','patrec_id');
    }

    public function patservice()
    {
        return $this->hasMany('App\PatientService', 'payment_id');
    }

    public function patpresc()
    {
        return $this->hasMany('App\PatientPrescription', 'payment_id');
    }

    public function patient()
    {
        return $this->belongsTo('App\Patient', 'patid');
    }

    public function getHashidAttribute()
    {
        return Hashids::encode($this->attributes['id']);
    }
}
