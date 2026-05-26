<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'website_title',
        'logo',
        'favicon',
        'contact_email',
        'contact_phone',
        'primary_color',
        'secondary_color',
        'sidebar_bg_color',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'announcement_text',
        'announcement_status',
    ];
}
