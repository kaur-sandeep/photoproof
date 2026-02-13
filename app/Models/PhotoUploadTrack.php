<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoUploadTrack extends Model
{
    use HasFactory;

    protected $table = 'photo_upload_tracks';

    protected $fillable = [
        'photo_detail_id',
        'user_id',
        'ip_address',
        'browser',
        'platform',
        'device',
        'device_type',
        'user_agent',
        'referer',
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
        'ip_query',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function photo()
    {
        return $this->belongsTo(PhotoDetail::class, 'photo_detail_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
