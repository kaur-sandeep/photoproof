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
        'billing_cycle',
        'price',
        'starts_at',
        'expires_at',
        'monthly_photo_limit',
        'monthly_photo_used',
        'topup_photo_limit',
        'topup_photo_used',
        'state',
    ];

    public function plan()
    {
    return $this->belongsTo(Subscriptionplans::class, 'subscription_plan_id', 'id');
    }

    public function organization() { return $this->belongsTo(Organization::class); }
    public function usages() { return $this->hasMany(OrganizationPhotoUsage::class, 'subscription_id'); }

    protected function casts(): array
    {
        return ['starts_at' => 'datetime', 'expires_at' => 'datetime', 'state' => 'boolean'];
    }
}
