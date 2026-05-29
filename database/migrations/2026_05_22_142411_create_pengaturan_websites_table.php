<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_websites', function (Blueprint $table) {
            $table->id();
            $table->string('nama_website')->default('Website Sekolah');
            $table->string('logo')->nullable();
            $table->string('logo_small')->nullable();
            $table->string('favicon')->nullable();
            $table->string('primary_color')->default('#4361ee');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_websites');
    }
};
