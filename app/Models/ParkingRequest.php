<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParkingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'society_id',
        'resident_id',
        'vehicle_name',
        'vehicle_number',
        'vehicle_type',
        'purpose',
        'status',
    ];

    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}
