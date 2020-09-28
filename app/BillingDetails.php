<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillingDetails extends Model
{
    protected $fillable = ['name', 'amount'];

    public function debit()
    {
        return $this->belongsTo('App\AccountDebit', 'debit_id');
    }

    public function service()
    {
        return $this->belongsTo('App\Service', 'serv_id');
    }

    public function presc()
    {
        return $this->belongsTo('App\PatientPrecription', 'presc_id');
    }
}
