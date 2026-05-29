<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kalender_akademiks', function (Blueprint $table) {
            $table->id();
            $table->string('judul_kegiatan', 200);
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('waktu', 50)->nullable();
            $table->string('tempat', 100)->nullable();
            $table->enum('jenis', ['kegiatan', 'ujian', 'libur', 'rapat', 'pendaftaran']);
            $table->string('target_audience', 100)->nullable();
            $table->string('warna', 20)->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kalender_akademiks');
    }
};
