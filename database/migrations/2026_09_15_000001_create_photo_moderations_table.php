<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('photo_moderations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photo_detail_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('device_id')->nullable()->index();
            $table->string('status');
            $table->string('reason')->nullable();
            $table->string('category')->nullable();
            $table->decimal('confidence', 6, 5)->nullable();
            $table->string('provider')->nullable();
            $table->json('response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photo_moderations');
    }
};
