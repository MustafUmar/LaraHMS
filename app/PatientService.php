<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientService extends Model
{
    protected $fillable = ['status', 'service_name', 'paid', 'mode'];

    public function services()
    {
        return $this->belongsTo('App\Service', 'service_id');
    }

    public function patrecord()
    {
        return $this->belongsTo('App\PatientRecord', 'patrec_id');
    }

    public function payment()
    {
        return $this->belongsTo('App\Payment', 'payment_id');
    }
}
