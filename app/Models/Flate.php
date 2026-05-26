<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flate extends Model
{
    protected $fillable = [
        'society_id',
        'building_id',
        'flate_number',
        'owner_name',
        'owner_phone',
        'owner_email',
        'floor',
        'status', // occupied, vacant, self-occupied
    ];

    public function getFlatNumberAttribute()
    {
        return $this->attributes['flate_number'] ?? null;
    }

    public function setFlatNumberAttribute($value)
    {
        $this->attributes['flate_number'] = $value;
    }

    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function residents(){
        return $this->hasMany(Resident::class);
    }

    public function parkingSlots()
    {
        return $this->hasMany(ParkingSlot::class, 'flate_id');
    }
}
