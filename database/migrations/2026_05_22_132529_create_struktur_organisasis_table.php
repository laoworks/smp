<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('struktur_organisasis', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200)->default('Struktur Organisasi Sekolah');
            $table->string('gambar', 255);
            $table->text('deskripsi')->nullable();
            $table->year('tahun')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('struktur_organisasis');
    }
};
