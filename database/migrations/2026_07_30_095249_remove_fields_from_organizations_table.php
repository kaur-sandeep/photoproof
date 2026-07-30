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
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('owner_name');
            $table->dropColumn('organization_email');
            $table->dropColumn('mobile_number');
            $table->dropColumn('password');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('owner_name')->nullable();
            $table->string('organization_email')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('password')->nullable();
        });
    }
};
