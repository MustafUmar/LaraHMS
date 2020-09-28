<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Vinkla\Hashids\Facades\Hashids;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'personnels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'personid', 'firstname', 'lastname', 'othername', 'email', 'phone', 'password', 'role', 'type', 'job_title',
        'isreset', 'active', 'last_login', 'last_activity', 'photo'
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

    protected $appends = ['hashid','fullname','phurl'];

    public function skills()
    {
        return $this->belongsToMany('App\Skill', 'doctor_skills', 'doc_id', 'skill_id');
    }

    public function patdoctor()
    {
        if($this->attributes['role'] == 'Doctor')
            return $this->hasMany('App\PatientDoctor', 'doc_id');
    }


    public function getFullnameAttribute()
    {
        return $this->firstname.' '.($this->othername?$this->othername.' ':'').$this->lastname;
        // return "{$this->firstname} {$this->othername?$this->othername.' ':''}{$this->lastname}";
    }

    // public function getPhotoAttribute($value)
    // {
    //     return $value ? '/storage/personnel/'.$value : null;
    // }

    public function getPhurlAttribute()
    {
        return $this->attributes['photo'] ? '/storage/personnel/'.$this->attributes['photo'] : null;
    }

    public function getHashidAttribute()
    {
        return Hashids::encode($this->attributes['id']);
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
