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
        'is_read'
    ];
}
