<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriptionplans extends Model
{
    public const BILLING_CYCLES = ['monthly', 'yearly'];

    protected $table = 'subscription_plans';
    protected $fillable = [
        'name',
        'code',
        'description',
        'monthly_photo_limit',
        'price',
        'yearly_price',
        'duration_days',
        'billing_cycle',
        'state',
    ];

    protected $casts = ['price' => 'decimal:2', 'yearly_price' => 'decimal:2', 'state' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('state', true);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'subscription_plan_id');
    }
}
