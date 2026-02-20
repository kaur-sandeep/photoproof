<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoReport extends Model
{
    protected $fillable = [
        'photo_random_id',
        'name',
        'email',
        'message',
        'is_read',
         'ip_address',
        'browser',
        'platform',
        'device',
        'device_type',
        'user_agent',
        'referer',
        'country',
        'region',
        'city',
        'zip',
        'latitude',
        'longitude',
        'timezone',
    ];
}
