<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['balance'];

    public function file()
    {
        return $this->belongsTo('App\PatientFile', 'file_id');
    }
}
