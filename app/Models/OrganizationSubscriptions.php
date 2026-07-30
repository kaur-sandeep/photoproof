<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Subscriptionplans;

class OrganizationSubscriptions extends Model
{
    protected $table = 'organization_subscriptions';
    protected $fillable = [
        'organization_id',
        'subscription_plan_id',
        'starts_at',
        'expires_at',
        'monthly_photo_limit',
        'monthly_photo_used',
        'max_employees',
        'status',
    ];

    public function plan()
    {
    return $this->belongsTo(Subscriptionplans::class, 'subscription_plan_id', 'id');
    }
}
