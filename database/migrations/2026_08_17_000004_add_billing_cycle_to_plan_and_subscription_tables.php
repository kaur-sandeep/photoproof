<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organization_subscriptions', function (Blueprint $table) {
            $table->string('billing_cycle', 10)->nullable()->after('subscription_plan_id');
            $table->decimal('price', 10, 2)->nullable()->after('billing_cycle');
        });

        // Only values with an unambiguous legacy meaning are converted. Other
        // historic durations intentionally remain null for manual review.
        foreach (['monthly' => 30, 'yearly' => 365] as $cycle => $duration) {
            $planIds = DB::table('subscription_plans')->where('duration_days', $duration)->pluck('id');
            DB::table('organization_subscriptions')->whereIn('subscription_plan_id', $planIds)->update(['billing_cycle' => $cycle]);
            DB::table('orders')->whereIn('subscription_plan_id', $planIds)->whereNull('billing_cycle')->update(['billing_cycle' => $cycle]);
        }

    }

    public function down(): void
    {
        Schema::table('organization_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['billing_cycle', 'price']);
        });
    }
};
