<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoModeration extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo_detail_id', 'user_id', 'device_id', 'status', 'reason',
        'category', 'confidence', 'provider', 'response',
    ];

    protected $casts = ['response' => 'array', 'confidence' => 'float'];
}
