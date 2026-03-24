<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppInstall extends Model
{
    protected $fillable = [
        'device_id',
        'platform',
        'app_version'
    ];
}
