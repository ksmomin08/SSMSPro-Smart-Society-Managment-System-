<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Society extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'email',
        'phone',
        'logo',
        'subscription_plan',
        'status',
        'expires_at',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function buildings()
    {
        return $this->hasMany(Building::class);
    }

    public function flats()
    {
        return $this->hasMany(Flate::class);
    }

    public function residents()
    {
        return $this->hasMany(Resident::class);
    }

    public function notices()
    {
        return $this->hasMany(Notice::class);
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function parkingSlots()
    {
        return $this->hasMany(ParkingSlot::class);
    }

    public function amenities()
    {
        return $this->hasMany(Amenity::class);
    }
}
