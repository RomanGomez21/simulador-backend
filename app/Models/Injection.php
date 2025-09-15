<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Injection extends Model
{
    protected $fillable= ['consumption_id','kwh_value'];
    
    public function consumption() {
        return $this->belongsTo(Consumption::class);
    }
}
