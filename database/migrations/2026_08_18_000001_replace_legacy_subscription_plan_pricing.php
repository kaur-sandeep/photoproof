<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Move the development-only subscription plans to explicit monthly and
     * yearly prices. Legacy duration and plan-level billing-cycle fields are
     * deliberately not carried forward: the chosen cycle belongs to an order
     * and its resulting subscription, not to a plan.
     */
    public function up(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->decimal('monthly_price', 10, 2)->nullable()->after('price');
        });

        DB::table('subscription_plans')->update([
            'monthly_price' => DB::raw('price'),
        ]);

        DB::table('subscription_plans')
            ->whereNull('yearly_price')
            ->update(['yearly_price' => DB::raw('monthly_price * 12')]);

        Schema::table('subscription_plans', function (Blueprint $table) {
            $columns = ['price', 'duration_days'];
            if (Schema::hasColumn('subscription_plans', 'billing_cycle')) {
                $columns[] = 'billing_cycle';
            }
            $table->dropColumn($columns);
        });
    }

    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->default(0)->after('monthly_photo_limit');
            $table->integer('duration_days')->default(30)->after('price');
            $table->string('billing_cycle', 10)->nullable()->after('duration_days');
        });

        DB::table('subscription_plans')->update([
            'price' => DB::raw('monthly_price'),
            'billing_cycle' => 'monthly',
        ]);

        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('monthly_price');
        });
    }
};
