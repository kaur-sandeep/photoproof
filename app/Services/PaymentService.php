<?php

namespace App\Services;

use App\Models\Notifications;
use App\Models\Order;
use App\Models\Setting;
use App\Notifications\CommonMailNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public function __construct(private SubscriptionService $subscriptions, private TopupService $topups) {}

    public function approveOffline(Order $order, array $payment): Order
    {
        $approvedOrder = DB::transaction(function () use ($order, $payment) {
            $order = Order::with(['payment', 'subscriptionPlan', 'topupPlan'])->lockForUpdate()->findOrFail($order->id);
            if ($order->payment_status === 'paid' || $order->status === 'completed') {
                throw ValidationException::withMessages(['order' => 'This order has already been approved.']);
            }
            if (! in_array($order->status, ['pending', 'processing'], true)) {
                throw ValidationException::withMessages(['order' => 'This order cannot be approved.']);
            }
            $order->payment->update([
                'payment_method' => 'offline', 'transaction_reference' => $payment['transaction_reference'] ?? null,
                'payment_date' => $payment['payment_date'] ?? now(), 'notes' => $payment['notes'] ?? null, 'status' => 'paid',
            ]);
            if ($order->order_type === 'topup') {
                $this->topups->applyOrder($order);
            } else {
                $this->subscriptions->activateOrder($order);
            }
            $order->update(['status' => 'completed', 'payment_status' => 'paid']);

            return $order->fresh(['payment', 'organization.users', 'subscriptionPlan', 'topupPlan']);
        });

        $this->sendApprovalNotifications($approvedOrder);

        return $approvedOrder;
    }

    private function sendApprovalNotifications(Order $order): void
    {
        try {
            $organization = $order->organization;
            $owner = $organization?->users->sortBy('created_at')->first();
            $itemName = $order->subscriptionPlan?->name ?? $order->topupPlan?->name ?? 'subscription';
            $message = "Payment for order {$order->order_number} has been approved.";
            $notificationData = [
                'message' => $message,
                'order_number' => $order->order_number,
                'item' => $itemName,
                'amount' => $order->amount,
            ];

            // A null organization ID is reserved for notifications shown to admins.
            Notifications::create([
                'photo_random_id' => $order->order_number,
                'name' => $organization?->organization_name ?? 'Organization',
                'email' => $owner?->email ?? '',
                'type' => 'Payment Approved',
                'organization_id' => null,
                'data' => json_encode($notificationData),
                'is_read' => false,
            ]);

            if ($organization) {
                Notifications::create([
                    'photo_random_id' => $order->order_number,
                    'name' => 'Payment Approved',
                    'email' => $owner?->email ?? '',
                    'type' => 'Payment Approved',
                    'organization_id' => $organization->id,
                    'data' => json_encode($notificationData),
                    'is_read' => false,
                ]);
            }

            $details = '<p>Payment for your order has been approved.</p>'
                .'<p><strong>Order number:</strong> '.e($order->order_number).'</p>'
                .'<p><strong>Plan Name:</strong> '.e($itemName).'</p>'
                .'<p><strong>Amount:</strong> $'.number_format((float) $order->amount, 2).'</p>';

            if ($owner?->email) {
                Notification::route('mail', $owner->email)->notify(new CommonMailNotification(
                    'Payment Approved - '.$order->order_number,
                    '<p>Dear '.e($owner->name).',</p>'.$details
                ));
            }

            $adminEmails = Setting::value('admin_email') ?: config('mail.from.address');
            $emails = array_filter(array_map('trim', explode(',', (string) $adminEmails)));
            if ($emails) {
                Notification::route('mail', $emails)->notify(new CommonMailNotification(
                    'Payment Approved - '.$order->order_number,
                    '<p>Dear Admin,</p><p>The requested payment has been approved.</p>'
                        .'<p><strong>Organization:</strong> '.e($organization?->organization_name ?? '--').'</p>'.$details
                ));
            }
        } catch (\Throwable $exception) {
            // Payment approval must remain successful even if a notification provider is unavailable.
            report($exception);
        }
    }
}
