<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek apakah kolom gelombang_ppdb_id ada
        if (Schema::hasColumn('pendaftars', 'gelombang_ppdb_id')) {
            // Rename ke gelombang_id
            Schema::table('pendaftars', function (Blueprint $table) {
                $table->renameColumn('gelombang_ppdb_id', 'gelombang_id');
            });
        }

        // Cek apakah kolom gelombang_id sudah ada
        if (!Schema::hasColumn('pendaftars', 'gelombang_id')) {
            Schema::table('pendaftars', function (Blueprint $table) {
                $table->foreignId('gelombang_id')->nullable()->constrained('gelombang_ppdbs')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            if (Schema::hasColumn('pendaftars', 'gelombang_id')) {
                $table->dropForeign(['gelombang_id']);
                $table->dropColumn('gelombang_id');
            }
        });
    }
};
