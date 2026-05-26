<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'society_id',
        'name',
        'description',
        'capacity',
        'status',
    ];

    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
