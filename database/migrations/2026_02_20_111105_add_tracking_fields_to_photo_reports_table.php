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
        Schema::table('photo_reports', function (Blueprint $table) {
             $table->string('ip_address')->nullable();
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            $table->string('device')->nullable();
            $table->string('device_type')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('referer')->nullable();

            $table->string('country')->nullable();
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            $table->string('zip')->nullable();

            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('timezone')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('photo_reports', function (Blueprint $table) {
               $table->dropColumn([
                'ip_address',
                'browser',
                'platform',
                'device',
                'device_type',
                'user_agent',
                'referer',
                'country',
                'region',
                'city',
                'zip',
                'latitude',
                'longitude',
                'timezone'
            ]);
        });
    }
};
