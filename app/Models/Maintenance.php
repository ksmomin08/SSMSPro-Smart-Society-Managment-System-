<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = [
        'society_id',
        'resident_id',
        'month',
        'amount',
        'late_fee',
        'due_date',
        'payment_status',
        'invoice_pdf',
    ];

    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
