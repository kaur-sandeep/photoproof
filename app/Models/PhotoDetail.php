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
        'state'
    ];

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
}
