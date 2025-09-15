<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subsidy extends Model
{
    protected $fillable=["structure_detail_id","type","charge_id","description","value"];
    
    public function structure_detail() 
    {
        return $this->belongsTo(StructureDetail::class);
    }
}
