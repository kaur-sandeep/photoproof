<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['order_number', 'organization_id', 'subscription_plan_id', 'topup_plan_id', 'order_type', 'amount', 'status', 'payment_status'];
    protected $casts = ['amount' => 'decimal:2'];

    public function organization() { return $this->belongsTo(Organization::class); }
    public function subscriptionPlan() { return $this->belongsTo(Subscriptionplans::class, 'subscription_plan_id'); }
    public function topupPlan() { return $this->belongsTo(TopupPlan::class); }
    public function payment() { return $this->hasOne(Payment::class); }
}
