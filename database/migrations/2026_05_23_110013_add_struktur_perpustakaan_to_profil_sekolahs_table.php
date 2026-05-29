<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profil_sekolahs', function (Blueprint $table) {
            if (!Schema::hasColumn('profil_sekolahs', 'struktur_perpustakaan')) {
                // Tambahkan kolom tanpa 'after' agar tidak error
                $table->string('struktur_perpustakaan', 255)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('profil_sekolahs', function (Blueprint $table) {
            if (Schema::hasColumn('profil_sekolahs', 'struktur_perpustakaan')) {
                $table->dropColumn('struktur_perpustakaan');
            }
        });
    }
};
