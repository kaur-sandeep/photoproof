<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Setting extends Model
{
     use HasFactory;
     protected $fillable = [
        'email_enabled',
        'smtp_enabled',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'delete_photos_after_days',
    ];

    // Cast smtp_enabled to boolean
    protected $casts = [
        'email_enabled' => 'boolean',
        'smtp_enabled' => 'boolean',
        'delete_photos_after_days' => 'integer',
        'smtp_port' => 'integer',
    ];
}
