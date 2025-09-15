<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StepCharge extends Model
{
    protected $fillable=['structure_detail_id','description','unit','min_range','max_range','value'];
    
    public function structure_detail() 
    {
        return $this->belongsTo(StructureDetail::class);
    }
}
