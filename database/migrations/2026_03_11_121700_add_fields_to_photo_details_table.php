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
            $table->tinyInteger('display_name')->default(0);
            $table->tinyInteger('display_location')->default(0);
            $table->tinyInteger('display_self_photo')->default(0);
            $table->tinyInteger('display_qrcode')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('photo_details', function (Blueprint $table) {
             $table->dropColumn(['display_name', 'display_location', 'display_self_photo', 'display_qrcode']);
        });
    }
};
