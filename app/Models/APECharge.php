<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class APECharge extends Model
{
    protected $table='ape_charges';
    protected $fillable= ['description','value'];

    public function energy_charges () {
        return $this->hasMany(EnergyCharge::class);
    }
}
