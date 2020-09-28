<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;
use Vinkla\Hashids\Facades\Hashids;

class Patient extends Authenticatable
{
    use Notifiable;

    protected $table = 'patients';

    protected $appends = ['hashid','fullname','age'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patno', 'title', 'firstname', 'lastname', 'othername', 'email', 'phone', 'password', 'dob', 'gender',
        'syndrome', 'bloodgroup', 'guardian', 'isreset', 'active', 'last_login', 'last_activity', 'main'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
//    protected $casts = [
//        'email_verified_at' => 'datetime',
//    ];

    public function file()
    {
        return $this->belongsTo('App\PatientFile','file_id');
    }

    public function patrecord()
    {
        return $this->hasMany('App\PatientRecord', 'patid');
    }

    public function payment()
    {
        return $this->hasMany('App\Payment', 'patid');
    }

    public function getFullnameAttribute()
    {
        return $this->firstname.' '.($this->othername?$this->othername.' ':'').$this->lastname;
        // return "{$this->firstname} {$this->othername?$this->othername.' ':''}{$this->lastname}";
    }

    public function getHashidAttribute()
    {
        return Hashids::encode($this->attributes['id']);
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->attributes['dob'])->age;
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['firstname'] = ucfirst($value);
    }

    public function setOtherNameAttribute($value)
    {
        $this->attributes['othername'] = ucfirst($value);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['lastname'] = ucfirst($value);
    }
}
