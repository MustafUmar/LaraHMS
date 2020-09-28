<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Surgery extends Model
{
    protected $fillable = ['status'];

    public function patrecord()
    {
        return $this->belongsTo('App\PatientRecord', 'patrec_id');
    }

    public function service()
    {
        return $this->belongsTo('App\Service', 'service_id');
    }

}
