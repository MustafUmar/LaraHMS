<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientDoctor extends Model
{
    protected $fillable = ['symptoms', 'diagnosis', 'date_diagnosed', 'observations', 'prescriptions'];

    public function patrecord()
    {
        return $this->belongsTo('App\PatientRecord', 'patrec_id');
    }

    public function doctor()
    {
        return $this->belongsTo('App\User', 'doc_id');
    }
}
