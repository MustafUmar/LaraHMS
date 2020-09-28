<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Emergency extends Model
{
    protected $fillable = ['injury', 'status', 'known', 'description'];
}
