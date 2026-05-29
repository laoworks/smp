<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi_ppdbs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained()->onDelete('cascade');
            $table->string('jenis_notifikasi', 50);
            $table->text('pesan');
            $table->enum('dikirim_via', ['email', 'whatsapp', 'sms']);
            $table->enum('status', ['pending', 'terkirim', 'gagal'])->default('pending');
            $table->datetime('dikirim_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi_ppdbs');
    }
};
