<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    protected $fillable = [
        'name', 'description', 'instock', 'rem', 'remunit', 'supplier_price', 'price', 'price_per_sat', 'price_per_gram',
        'price_per_pill', 'price_per_tab', 'price_per_pack', 'price_per_ml', 'price_per_shot'
    ];

    public function med()
    {
        return $this->hasMany('App\MedPrescription', 'pharm_id');
    }
}
