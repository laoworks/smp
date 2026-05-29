<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profil_sekolahs', function (Blueprint $table) {
            // Pastikan kolom visi dan misi ada
            if (!Schema::hasColumn('profil_sekolahs', 'visi')) {
                $table->text('visi')->nullable()->after('sejarah');
            }
            if (!Schema::hasColumn('profil_sekolahs', 'misi')) {
                $table->text('misi')->nullable()->after('visi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('profil_sekolahs', function (Blueprint $table) {
            $table->dropColumn(['visi', 'misi']);
        });
    }
};
