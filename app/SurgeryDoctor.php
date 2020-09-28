<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurgeryDoctor extends Model
{
    protected $fillable = ['starttime', 'endtime'];

    public function surgery()
    {
        return $this->belongsTo('App\Surgery', 'sug_id');
    }

    public function doctor()
    {
        return $this->belongsTo('App\Personnel', 'doc_id');
    }
}
