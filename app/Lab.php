<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    protected $fillable = ['testname','status','result','result_date','comment'];

    public function patrecord()
    {
        return $this->belongsTo('App\PatientRecord', 'patrec_id');
    }

    public function service()
    {
        return $this->belongsTo('App\Service', 'service_id');
    }

}
