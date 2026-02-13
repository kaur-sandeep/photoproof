<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::create('photo_views', function (Blueprint $table) {
        $table->id();
        $table->foreignId('photo_detail_id')->constrained()->onDelete('cascade');
        $table->string('ip_address')->nullable();
        $table->string('browser')->nullable();
        $table->string('platform')->nullable();
        $table->string('device')->nullable();
        $table->string('device_type')->nullable();
        $table->text('referer')->nullable();
        $table->text('user_agent')->nullable();
        $table->string('country')->nullable();
        $table->string('country_code')->nullable();
        $table->string('region')->nullable();
        $table->string('region_name')->nullable();
        $table->string('city')->nullable();
        $table->string('zip')->nullable();
        $table->decimal('latitude', 10, 7)->nullable();
        $table->decimal('longitude', 10, 7)->nullable();
        $table->string('timezone')->nullable();
        $table->string('isp')->nullable();
        $table->string('org')->nullable();
        $table->string('as_name')->nullable();
        $table->string('ip_query')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_views');
    }
};
