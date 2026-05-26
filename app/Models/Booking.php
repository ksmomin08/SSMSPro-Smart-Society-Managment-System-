<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'society_id',
        'amenity_id',
        'resident_id',
        'booking_date',
        'start_time',
        'end_time',
        'status',
    ];

    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    public function amenity()
    {
        return $this->belongsTo(Amenity::class);
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}
