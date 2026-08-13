<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public function __construct(private SubscriptionService $subscriptions, private TopupService $topups) {}

    public function approveOffline(Order $order, array $payment): Order
    {
        return DB::transaction(function () use ($order, $payment) {
            $order = Order::with(['payment', 'subscriptionPlan', 'topupPlan'])->lockForUpdate()->findOrFail($order->id);
            if ($order->payment_status === 'paid' || $order->status === 'completed') {
                throw ValidationException::withMessages(['order' => 'This order has already been approved.']);
            }
            if (!in_array($order->status, ['pending', 'processing'], true)) {
                throw ValidationException::withMessages(['order' => 'This order cannot be approved.']);
            }
            $order->payment->update([
                'payment_method' => 'offline', 'transaction_reference' => $payment['transaction_reference'] ?? null,
                'payment_date' => $payment['payment_date'] ?? now(), 'notes' => $payment['notes'] ?? null, 'status' => 'paid',
            ]);
            if ($order->order_type === 'topup') $this->topups->applyOrder($order);
            else $this->subscriptions->activateOrder($order);
            $order->update(['status' => 'completed', 'payment_status' => 'paid']);
            return $order->fresh(['payment']);
        });
    }
}
