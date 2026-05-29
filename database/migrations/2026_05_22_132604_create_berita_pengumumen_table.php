<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita_pengumumans', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->string('slug', 200)->unique();
            $table->enum('jenis', ['berita', 'pengumuman']);
            $table->boolean('is_urgent')->default(false);
            $table->longText('konten');
            $table->string('gambar_utama', 255)->nullable();
            $table->string('penulis', 100)->nullable();
            $table->integer('views')->default(0);
            $table->boolean('is_published')->default(false);
            $table->datetime('published_at')->nullable();
            $table->datetime('expired_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_pengumumans');
    }
};
