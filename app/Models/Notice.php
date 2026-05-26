<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = [
        'society_id',
        'title',
        'category', // Announcement, Emergency, Event
        'description',
        'attachment',
        'notice_date',
        'scheduled_at',
    ];

    public function society()
    {
        return $this->belongsTo(Society::class);
    }
}
