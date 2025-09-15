<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    public function period_years()
    {
        return $this->hasMany(PeriodYear::class);
    }
}
