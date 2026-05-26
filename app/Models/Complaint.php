<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'society_id',
        'resident_id',
        'title',
        'category',
        'priority',
        'description',
        'attachment',
        'status',
        'admin_reply',
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
