<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inoculation extends Model
{
    protected $fillable = ['type', 'no_of_shots', 'interval', 'interval_times', 'status'];
}
