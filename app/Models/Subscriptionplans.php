<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriptionplans extends Model
{
    protected $table = 'subscription_plans';
    protected $fillable = [
        'name',
        'code',
        'description',
        'monthly_photo_limit',
        'price',
        'duration_days',
        'state',
    ];

    protected $casts = ['price' => 'decimal:2', 'state' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('state', true);
    }
}
