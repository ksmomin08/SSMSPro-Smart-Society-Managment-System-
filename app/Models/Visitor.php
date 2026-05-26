<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'society_id',
        'resident_id',
        'visitor_name',
        'visitor_code',
        'mobile',
        'vehicle_number',
        'purpose',
        'delivery_company',
        'visit_date',
        'entry_time',
        'exit_time',
        'status', // Pending Approval, Approved, Checked In, Checked Out, Rejected
        'photo',
        'otp',
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
