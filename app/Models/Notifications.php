<?php

namespace App\Models;
use App\Models\PhotoDetail;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $fillable = [
        'photo_random_id',
        'name',
        'email',
        'type',
        'data',
        'organization_id',
        'is_read'
    ];


    public function photoDetail()
{
    return $this->belongsTo(PhotoDetail::class, 'photo_random_id', 'random_id');
}
}
