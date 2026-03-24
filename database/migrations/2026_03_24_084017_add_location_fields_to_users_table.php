<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
           
            $table->string('location')->nullable()->after('state');
            $table->string('country')->nullable()->after('location');
            $table->string('city')->nullable()->after('country');
            $table->string('region_name')->nullable()->after('city');
            $table->string('zip')->nullable()->after('region_name');
            $table->string('device_type')->nullable()->after('zip');
            $table->string('timezone')->nullable()->after('device_type');
     
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
              $table->dropColumn([
                'location',
                'country',
                'city',
                'region_name',
                'zip',
                'device_type',
                'timezone'
            ]);
        });
    }
};
