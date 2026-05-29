<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 50)->unique()->nullable();
            $table->string('nuptk', 50)->nullable();
            $table->string('nama_lengkap', 100);
            $table->string('gelar_depan', 20)->nullable();
            $table->string('gelar_belakang', 20)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('pendidikan_terakhir', 100)->nullable();
            $table->string('jurusan', 100)->nullable();
            $table->string('universitas', 100)->nullable();
            $table->year('tahun_lulus')->nullable();
            $table->string('mata_pelajaran', 100)->nullable();
            $table->string('jabatan', 100)->nullable();
            $table->enum('status', ['pns', 'pppk', 'honorer', 'tetap_yayasan'])->nullable();
            $table->string('foto', 255)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
