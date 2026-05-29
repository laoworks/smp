<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeri_videos', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->text('deskripsi')->nullable();
            $table->string('url_youtube', 255)->nullable();
            $table->text('embed_code')->nullable();
            $table->string('thumbnail', 255)->nullable();
            $table->date('tanggal')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeri_videos');
    }
};
