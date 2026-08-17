<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->string('billing_cycle', 10)->default('monthly')->after('price');
        });

        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->string('billing_cycle', 10)->nullable()->after('duration_days');
        });

        Schema::table('organization_subscriptions', function (Blueprint $table) {
            $table->string('billing_cycle', 10)->nullable()->after('subscription_plan_id');
            $table->decimal('price', 10, 2)->nullable()->after('billing_cycle');
        });

        // Only values with an unambiguous legacy meaning are converted. Other
        // historic durations intentionally remain null for manual review.
        DB::table('subscription_plans')->where('duration_days', 30)->update(['billing_cycle' => 'monthly']);
        DB::table('subscription_plans')->where('duration_days', 365)->update(['billing_cycle' => 'yearly']);

        foreach (['monthly' => 30, 'yearly' => 365] as $cycle => $duration) {
            $planIds = DB::table('subscription_plans')->where('duration_days', $duration)->pluck('id');
            DB::table('organization_subscriptions')->whereIn('subscription_plan_id', $planIds)->update(['billing_cycle' => $cycle]);
            DB::table('orders')->whereIn('subscription_plan_id', $planIds)->whereNull('billing_cycle')->update(['billing_cycle' => $cycle]);
        }

        $unknownDurations = DB::table('subscription_plans')->whereNotIn('duration_days', [30, 365])->distinct()->pluck('duration_days');
        if ($unknownDurations->isNotEmpty()) {
            Log::warning('Subscription plans with an unmapped legacy duration were not assigned a billing cycle.', ['duration_days' => $unknownDurations->all()]);
        }
    }

    public function down(): void
    {
        Schema::table('organization_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['billing_cycle', 'price']);
        });
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('billing_cycle');
        });
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('billing_cycle');
        });
    }
};
