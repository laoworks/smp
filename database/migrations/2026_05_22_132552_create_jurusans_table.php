<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurusans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jurusan', 20)->unique();
            $table->string('nama_jurusan', 100);
            $table->string('singkatan', 20)->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('kurikulum')->nullable();
            $table->text('prospek_karir')->nullable();
            $table->text('fasilitas')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->string('thumbnail', 255)->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jurusans');
    }
};
