<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StructureDetail extends Model
{
    protected $fillable=['structure_id','subcategory_id'];
    public function structure()
    {
        return $this->belongsTo(Structure::class, 'structure_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    public function fixed_charges () {
        return $this->hasMany(FixedCharge::class);
    }

    public function step_charges () 
    {
        return $this->hasMany(StepCharge::class);
    }

    public function subsidies () 
    {
        return $this->hasMany(Subsidy::class);
    }

     public function energy_charges () 
    {
        return $this->hasMany(EnergyCharge::class);
    }

    public function energy_injection_charges () 
    {
        return $this->hasMany(EnergyInjectionCharge::class);
    }

    //Solo tendrá asociado 2 consumos, y uno de estos dos tendrá asociado una inyección

    public function consumptions () 
    {
        return $this->hasMany(Consumption::class);
    }
}
