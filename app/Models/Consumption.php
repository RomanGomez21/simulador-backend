<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consumption extends Model
{
   protected $fillable=['structure_detail_id','kwh_value','kvarh_value','kw_value'];

    public function structure_detail() 
    {
        return $this->belongsTo(StructureDetail::class);
    }

    public function injection()
    {
        return $this->hasOne(Injection::class);
    }

}
