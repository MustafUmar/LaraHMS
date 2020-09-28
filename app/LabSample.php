<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabSample extends Model
{
    protected $fillable = ['type', 'tagno'];

    public function lab()
    {
        return $this->belongsTo('App\Lab', 'lab_id');
    }

    public function pat()
    {
        return $this->belongsTo('App\Patient', 'pat_id');
    }
}
