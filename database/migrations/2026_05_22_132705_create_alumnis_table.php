<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumnis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 100);
            $table->year('tahun_lulus')->nullable();
            $table->foreignId('jurusan_id')->nullable()->constrained()->nullOnDelete();
            $table->string('foto', 255)->nullable();

            // Pendidikan lanjutan
            $table->string('universitas', 100)->nullable();
            $table->string('jurusan_kuliah', 100)->nullable();
            $table->year('tahun_masuk_kuliah')->nullable();

            // Pekerjaan
            $table->string('pekerjaan', 100)->nullable();
            $table->string('perusahaan', 100)->nullable();
            $table->string('posisi', 100)->nullable();

            // Kontak
            $table->string('email', 100)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('linkedin', 100)->nullable();

            // Kisah sukses
            $table->text('kisah_sukses')->nullable();
            $table->text('prestasi_alumni')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumnis');
    }
};
