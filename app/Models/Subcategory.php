<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    public function structure_details() 
    {
        return $this->hasMany(StructureDetail::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
