<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnergyInjectionCharge extends Model
{
    protected $fillable=['structure_detail_id','description','value'];
    
    public function structure_detail() 
    {
        return $this->belongsTo(StructureDetail::class);
    }
}
