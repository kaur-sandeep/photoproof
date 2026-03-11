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
            $table->renameColumn('display_name', 'display_name_flag');
            $table->renameColumn('display_location', 'display_location_flag');
            $table->renameColumn('display_self_photo', 'display_self_photo_flag');
            $table->renameColumn('display_qrcode', 'display_qrcode_flag');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('photo_details', function (Blueprint $table) {
            $table->renameColumn('display_name_flag', 'display_name');
            $table->renameColumn('display_location_flag', 'display_location');
            $table->renameColumn('display_self_photo_flag', 'display_self_photo');
            $table->renameColumn('display_qrcode_flag', 'display_qrcode');
        });
    }
};
