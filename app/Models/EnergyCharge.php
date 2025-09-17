<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnergyCharge extends Model
{
    protected $fillable=["structure_detail_id","energy_price_id","ape_charge_id","description","min_range","max_range","value"];

    public function structure_detail() 
    {
        return $this->belongsTo(StructureDetail::class);
    }

    public function ape_charge() 
    {
        return $this->belongsTo(APECharge::class);
    }

    public function energy_price () {
        return $this->belongsTo(EnergyPrice::class);
    }
}
