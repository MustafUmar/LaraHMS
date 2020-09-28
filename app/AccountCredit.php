<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountCredit extends Model
{
    protected $fillable = ['amount', 'pay_meth', 'trans_id', 'crediter', ];

    public function account()
    {
        return $this->belongsTo('App\Account', 'acc_id');
    }

    public function patient()
    {
        return $this->belongsTo('App\Patient', 'pat_id');
    }

    public function creditedBy()
    {
        return $this->belongsTo('App\User', 'credited_by');
    }
}
