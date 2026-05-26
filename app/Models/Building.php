<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = [
        'society_id',
        'building_name',
        'building_code'
    ];

    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    public function flats()
    {
        return $this->hasMany(Flate::class);
    }
}
