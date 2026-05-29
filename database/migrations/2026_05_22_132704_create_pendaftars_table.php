<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftars', function (Blueprint $table) {
            $table->id();
            $table->string('no_pendaftaran', 20)->unique();
            $table->foreignId('gelombang_id')->constrained('gelombang_ppdbs');

            // Data pribadi
            $table->string('nama_lengkap', 100);
            $table->string('nik', 20)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('agama', 20)->nullable();
            $table->integer('anak_ke')->nullable();
            $table->integer('jumlah_saudara')->nullable();

            // Kontak
            $table->string('email', 100)->nullable();
            $table->string('no_hp', 20);
            $table->text('alamat')->nullable();
            $table->string('rt_rw', 20)->nullable();
            $table->string('kelurahan', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('provinsi', 100)->nullable();

            // Sekolah asal
            $table->string('asal_sekolah', 100)->nullable();
            $table->string('nisn', 20)->nullable();
            $table->year('tahun_lulus')->nullable();
            $table->string('ijazah_number', 50)->nullable();

            // Data orang tua
            $table->string('ayah_nama', 100)->nullable();
            $table->string('ayah_pekerjaan', 100)->nullable();
            $table->string('ayah_pendidikan', 50)->nullable();
            $table->string('ayah_no_hp', 20)->nullable();
            $table->string('ibu_nama', 100)->nullable();
            $table->string('ibu_pekerjaan', 100)->nullable();
            $table->string('ibu_pendidikan', 50)->nullable();
            $table->string('ibu_no_hp', 20)->nullable();
            $table->string('wali_nama', 100)->nullable();
            $table->string('wali_pekerjaan', 100)->nullable();
            $table->string('wali_hubungan', 50)->nullable();

            // Jurusan pilihan
            $table->foreignId('jurusan_pilihan_1')->nullable()->constrained('jurusans');
            $table->foreignId('jurusan_pilihan_2')->nullable()->constrained('jurusans');

            // Dokumen
            $table->string('foto_kk', 255)->nullable();
            $table->string('foto_akte', 255)->nullable();
            $table->string('foto_ijazah', 255)->nullable();
            $table->string('foto_nilai', 255)->nullable();
            $table->string('foto_sertifikat', 255)->nullable();

            // Status
            $table->enum('status_verifikasi', ['pending', 'verifikasi', 'diterima', 'cadangan', 'ditolak'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->timestamp('tanggal_daftar')->useCurrent();
            $table->datetime('tanggal_verifikasi')->nullable();
            $table->foreignId('verifikator_id')->nullable()->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftars');
    }
};
