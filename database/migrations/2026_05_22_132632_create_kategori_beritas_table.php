<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_beritas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 50);
            $table->string('slug', 50)->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Tabel pivot berita_kategori
        Schema::create('berita_kategori', function (Blueprint $table) {
            $table->foreignId('berita_id')->constrained('berita_pengumumans')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('kategori_beritas')->onDelete('cascade');
            $table->primary(['berita_id', 'kategori_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_kategori');
        Schema::dropIfExists('kategori_beritas');
    }
};
