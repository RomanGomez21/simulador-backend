<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Structure extends Model
{
    protected $fillable=['description'];
    
    public function period_structure_years()
    {
        return $this->hasMany(PeriodStructureYear::class);
    }

    public function structure_details()
    {
        return $this->hasMany(StructureDetail::class);
    }
}
