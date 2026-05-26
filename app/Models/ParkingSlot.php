<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParkingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'society_id',
        'slot_number',
        'vehicle_type',
        'status',
        'flate_id',
    ];

    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    public function flat()
    {
        return $this->belongsTo(Flate::class, 'flate_id');
    }
}
