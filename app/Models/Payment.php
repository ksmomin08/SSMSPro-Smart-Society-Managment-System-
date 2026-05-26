<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_id',
        'resident_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status',
        'receipt_number',
    ];

    public function maintenance()
    {
        return $this->belongsTo(Maintenance::class);
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}
