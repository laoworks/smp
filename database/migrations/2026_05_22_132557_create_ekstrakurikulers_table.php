<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ekstrakurikulers', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ekskul', 100);
            $table->foreignId('pembina_id')->nullable()->constrained('gurus')->nullOnDelete();
            $table->text('deskripsi')->nullable();
            $table->text('prestasi')->nullable();
            $table->string('jadwal_latihan', 200)->nullable();
            $table->string('tempat_latihan', 100)->nullable();
            $table->string('gambar', 255)->nullable();
            $table->integer('kuota')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ekstrakurikulers');
    }
};
