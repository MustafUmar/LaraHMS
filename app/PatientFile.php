<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;

class PatientFile extends Model
{
    protected $fillable = ['fileno', 'type', 'active', 'pmod'];

    protected $appends = ['hashid'];

    public function patients()
    {
        return $this->hasMany('App\Patient','file_id');
    }

    public function getHashidAttribute()
    {
        return Hashids::encode($this->attributes['id']);
    }
}
