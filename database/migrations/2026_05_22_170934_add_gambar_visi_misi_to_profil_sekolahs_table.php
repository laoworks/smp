<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profil_sekolahs', function (Blueprint $table) {
            // Gambar untuk Visi
            if (!Schema::hasColumn('profil_sekolahs', 'gambar_visi')) {
                $table->string('gambar_visi', 255)->nullable()->after('visi');
            }
            // Gambar untuk Misi 1
            if (!Schema::hasColumn('profil_sekolahs', 'gambar_misi_1')) {
                $table->string('gambar_misi_1', 255)->nullable()->after('misi');
            }
            // Gambar untuk Misi 2
            if (!Schema::hasColumn('profil_sekolahs', 'gambar_misi_2')) {
                $table->string('gambar_misi_2', 255)->nullable()->after('gambar_misi_1');
            }
            // Gambar untuk Misi 3
            if (!Schema::hasColumn('profil_sekolahs', 'gambar_misi_3')) {
                $table->string('gambar_misi_3', 255)->nullable()->after('gambar_misi_2');
            }
            // Judul Misi 1,2,3 (opsional)
            if (!Schema::hasColumn('profil_sekolahs', 'judul_misi_1')) {
                $table->string('judul_misi_1', 100)->nullable()->after('gambar_misi_1');
            }
            if (!Schema::hasColumn('profil_sekolahs', 'judul_misi_2')) {
                $table->string('judul_misi_2', 100)->nullable()->after('gambar_misi_2');
            }
            if (!Schema::hasColumn('profil_sekolahs', 'judul_misi_3')) {
                $table->string('judul_misi_3', 100)->nullable()->after('gambar_misi_3');
            }
            // Deskripsi Misi 1,2,3
            if (!Schema::hasColumn('profil_sekolahs', 'deskripsi_misi_1')) {
                $table->text('deskripsi_misi_1')->nullable()->after('judul_misi_1');
            }
            if (!Schema::hasColumn('profil_sekolahs', 'deskripsi_misi_2')) {
                $table->text('deskripsi_misi_2')->nullable()->after('judul_misi_2');
            }
            if (!Schema::hasColumn('profil_sekolahs', 'deskripsi_misi_3')) {
                $table->text('deskripsi_misi_3')->nullable()->after('judul_misi_3');
            }
        });
    }

    public function down(): void
    {
        Schema::table('profil_sekolahs', function (Blueprint $table) {
            $table->dropColumn([
                'gambar_visi',
                'gambar_misi_1',
                'gambar_misi_2',
                'gambar_misi_3',
                'judul_misi_1',
                'judul_misi_2',
                'judul_misi_3',
                'deskripsi_misi_1',
                'deskripsi_misi_2',
                'deskripsi_misi_3'
            ]);
        });
    }
};
