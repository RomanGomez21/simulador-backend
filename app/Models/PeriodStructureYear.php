<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodStructureYear extends Model
{
    protected $table='period_structure_year';
    protected $fillable= ['structure_id','period_year_id'];

    public function period_year()
    {
        return $this->belongsTo(PeriodYear::class, 'period_year_id');
    }

    public function structure()
    {
        return $this->belongsTo(Structure::class, 'structure_id');
    }

}
