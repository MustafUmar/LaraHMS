<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountDebit extends Model
{
    protected $fillable = ['amount', 'debiter'];

    public function account()
    {
        return $this->belongsTo('App\Account', 'acc_id');
    }

    public function patient()
    {
        return $this->belongsTo('App\Patient', 'pat_id');
    }

    public function payment()
    {
        return $this->belongsTo('App\Payment', 'pay_id');
    }

    public function debiter()
    {
        return $this->belongsTo('App\User', 'debited_by');
    }
}
