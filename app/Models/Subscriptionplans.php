<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriptionplans extends Model
{
    protected $table = 'subscription_plans';
    protected $fillable = [
        'name',
        'code',
        'monthly_photo_limit',
        'max_employees',
        'organization_code',
        'price',
        'duration_days',
        'status',
    ];
}
