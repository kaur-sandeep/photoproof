<?php

namespace App\Services;

use App\Models\OrganizationPhotoUsage;
use App\Models\PhotoDetail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class OrganizationPhotoLimitService
{
    public function __construct(private SubscriptionService $subscriptions) {}

    public function consume(User $user, PhotoDetail $photo): void
    {
        DB::transaction(function () use ($user, $photo) {
            $subscription = $this->subscriptions->activeForOrganization($user->organization_id, true);
            if (!$subscription) throw new RuntimeException('Your account subscription has expired. Please renew your plan.');
            $type = $subscription->monthly_photo_used < $subscription->monthly_photo_limit ? 'monthly' :
                ($subscription->topup_photo_used < $subscription->topup_photo_limit ? 'topup' : null);
            if (!$type) throw new RuntimeException('Account photo capacity has been reached. Please renew or purchase a top-up.');
            $subscription->increment($type === 'monthly' ? 'monthly_photo_used' : 'topup_photo_used');
            OrganizationPhotoUsage::create([
                'organization_id' => $user->organization_id, 'subscription_id' => $subscription->id,
                'user_id' => $user->id, 'photo_id' => $photo->id, 'usage_type' => $type,
                'usage_date' => today(), 'photo_count' => 1,
            ]);
        });
    }
}
