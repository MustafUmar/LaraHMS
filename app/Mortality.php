<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mortality extends Model
{
    protected $fillable = ['tagno','known','name','cause','time_death'];
}
