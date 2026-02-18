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
         Schema::table('photo_details', function (Blueprint $table) {
            $table->dateTime('word_api_date_time')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            $table->string('device_type')->nullable();
            $table->string('device_brand')->nullable();
            $table->string('device_model')->nullable();
            $table->string('device_name')->nullable();
            $table->string('device_manufacturer')->nullable();

            $table->string('android_version')->nullable();
            $table->string('android_sdk')->nullable();

            $table->string('ios_system_version')->nullable();
            $table->string('ios_identifier')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('photo_details', function (Blueprint $table) {
            //
        });
    }
};
