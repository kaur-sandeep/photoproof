<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'random_id',
        'user_id',
        'name',
        'location',
        'photo',
        'state',
        'word_api_date_time',
        'latitude',
        'longitude',
        'device_type',
        'device_brand',
        'device_model',
        'device_name',
        'device_manufacturer',
        'android_version',
        'android_sdk',
        'ios_system_version',
        'ios_identifier',
        'country',
        'country_code',
        'region',
        'region_name',
        'city',
        'zip',
        'display_name_flag',
        'display_location_flag',
        'display_self_photo_flag',
        'display_qrcode',
        'display_qrcode_flag',
        'meta_data'
    ];

    protected $casts = [
    'meta_data' => 'array',
];



    // id =  random_id ,word_api_date_time,latitude,longitude,device_type,device_brand,device_model,device_name,device_manufacturer,android_version,android_sdk,ios_system_version,ios_identifier

    // 👇 Add this
    protected $appends = ['photo_url'];

    // 👇 Create accessor
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }

        return null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function views()
    {
        return $this->hasMany(PhotoView::class, 'photo_detail_id');
    }
  public function uploadTrack()
    {
        return $this->hasOne(PhotoUploadTrack::class, 'photo_detail_id');
    }
}
