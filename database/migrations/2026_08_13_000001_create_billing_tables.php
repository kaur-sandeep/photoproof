<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topup_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->unsignedInteger('photo_quantity');
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('state')->default(true);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('organization_id')->constrained()->restrictOnDelete();
            $table->foreignId('subscription_plan_id')->nullable()->constrained('subscription_plans')->restrictOnDelete();
            $table->foreignId('topup_plan_id')->nullable()->constrained('topup_plans')->restrictOnDelete();
            $table->enum('order_type', ['subscription', 'renewal', 'topup']);
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled', 'expired'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->timestamps();
            $table->index(['organization_id', 'status']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('payment_method')->default('offline');
            $table->string('transaction_reference')->nullable();
            $table->decimal('amount', 10, 2);
            $table->timestamp('payment_date')->nullable();
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('organization_photo_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->restrictOnDelete();
            $table->foreignId('subscription_id')->constrained('organization_subscriptions')->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('photo_id')->constrained('photo_details')->restrictOnDelete();
            $table->enum('usage_type', ['monthly', 'topup']);
            $table->date('usage_date');
            $table->unsignedInteger('photo_count')->default(1);
            $table->timestamps();
            $table->unique('photo_id');
            $table->index(['organization_id', 'usage_date']);
        });

        Schema::table('organization_subscriptions', function (Blueprint $table) {
            $table->unsignedInteger('topup_photo_limit')->default(0)->after('monthly_photo_used');
            $table->unsignedInteger('topup_photo_used')->default(0)->after('topup_photo_limit');
            $table->index(['organization_id', 'state', 'starts_at', 'expires_at'], 'org_subscription_active_lookup');
        });
    }

    public function down(): void
    {
        Schema::table('organization_subscriptions', function (Blueprint $table) {
            $table->dropIndex('org_subscription_active_lookup');
            $table->dropColumn(['topup_photo_limit', 'topup_photo_used']);
        });
        Schema::dropIfExists('organization_photo_usage');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('topup_plans');
    }
};
