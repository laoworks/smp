<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesan_kontaks', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('email', 100);
            $table->string('no_hp', 20)->nullable();
            $table->string('subjek', 200)->nullable();
            $table->text('pesan');
            $table->boolean('is_read')->default(false);
            $table->boolean('is_replied')->default(false);
            $table->datetime('replied_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesan_kontaks');
    }
};
