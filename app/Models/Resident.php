<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    protected $fillable = [
        'society_id',
        'flate_id',
        'user_id',
        'name',
        'email',
        'phone',
        'family_members',
        'image',
    ];

    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function flats(){
        return $this->belongsTo(Flate::class, 'flate_id');
    }

    public function flat(){
        return $this->belongsTo(Flate::class, 'flate_id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function parkingRequests()
    {
        return $this->hasMany(ParkingRequest::class);
    }
}
