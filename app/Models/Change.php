<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Change extends Model
{
    public function period_year()
    {
        return $this->belongsTo(PeriodYear::class, 'period_year_id');
    }
}
