<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnergyPrice extends Model
{
    protected $fillable=['description','value'];
    
    public function energy_charges () {
        return $this->hasMany(EnergyCharge::class);
    }
}
