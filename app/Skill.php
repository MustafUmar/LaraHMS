<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;

class Skill extends Model
{
    protected $fillable = ['name', 'person', 'description'];

    protected $appends = ['hashid'];

    public function doctors()
    {
        return $this->belongsToMany('App\User', 'doctor_skills', 'skill_id', 'doc_id');
    }

    public function getHashidAttribute()
    {
        return Hashids::encode($this->attributes['id']);
    }
}
