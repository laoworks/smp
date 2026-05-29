<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->string('jenis', 50)->nullable();
            $table->string('tingkat', 50)->nullable();
            $table->string('juara', 100)->nullable();
            $table->year('tahun')->nullable();
            $table->string('peserta_nama', 100)->nullable();
            $table->string('peserta_kelas', 20)->nullable();
            $table->string('sertifikat', 255)->nullable();
            $table->string('foto', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasis');
    }
};
