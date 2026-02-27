<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class notifications extends Model
{
    protected $fillable = [
        'photo_random_id',
        'name',
        'email',
        'type',
        'data',
        'is_read'
    ];
}
