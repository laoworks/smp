<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profil_sekolahs', function (Blueprint $table) {
            if (!Schema::hasColumn('profil_sekolahs', 'sambutan_kepala_sekolah')) {
                $table->text('sambutan_kepala_sekolah')->nullable();
            }
            if (!Schema::hasColumn('profil_sekolahs', 'gambar_kepala_sekolah')) {
                $table->string('gambar_kepala_sekolah', 255)->nullable();
            }
            if (!Schema::hasColumn('profil_sekolahs', 'nama_kepala_sekolah')) {
                $table->string('nama_kepala_sekolah', 100)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('profil_sekolahs', function (Blueprint $table) {
            $table->dropColumn(['sambutan_kepala_sekolah', 'gambar_kepala_sekolah', 'nama_kepala_sekolah']);
        });
    }
};
