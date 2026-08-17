<?php

namespace App\Services;

use Carbon\CarbonInterface;
use InvalidArgumentException;

class BillingCycleService
{
    public const MONTHLY = 'monthly';
    public const YEARLY = 'yearly';

    public static function expiry(CarbonInterface $startsAt, string $billingCycle): CarbonInterface
    {
        return match ($billingCycle) {
            self::MONTHLY => $startsAt->copy()->addMonth(),
            self::YEARLY => $startsAt->copy()->addYear(),
            default => throw new InvalidArgumentException('Invalid subscription billing cycle.'),
        };
    }
}
