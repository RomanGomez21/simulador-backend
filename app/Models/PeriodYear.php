<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodYear extends Model
{
    protected $table='period_year';
    protected $fillable= ['period_id','year_id'];

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

     public function period()
    {
        return $this->belongsTo(Period::class);
    }

     public function change()
    {
        return $this->hasOne(Change::class,'period_year_id');
    }

     public function period_structure_year()
    {
        return $this->hasOne(PeriodStructureYear::class,'period_year_id');
    }

}
