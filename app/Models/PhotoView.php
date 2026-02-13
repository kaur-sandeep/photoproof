<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoView extends Model
{
    protected $fillable = [
         'photo_detail_id',
        'ip_address',
        'browser',
        'platform',
        'device',
        'device_type',
        'referer',
        'user_agent',

        'country',
        'country_code',
        'region',
        'region_name',
        'city',
        'zip',
        'latitude',
        'longitude',
        'timezone',
        'isp',
        'org',
        'as_name',
        'ip_query'
        ];

    public function photo()
    {
        return $this->belongsTo(PhotoDetail::class, 'photo_detail_id');
    }
}
