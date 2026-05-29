<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom role
            $table->enum('role', ['superadmin', 'admin', 'verifikator', 'guru', 'waka'])
                ->default('admin')
                ->after('email');

            // Tambahkan kolom is_active
            $table->boolean('is_active')->default(true)->after('role');

            // Tambahkan kolom foto (opsional)
            $table->string('foto', 255)->nullable()->after('is_active');

            // Tambahkan kolom last_login (opsional)
            $table->datetime('last_login')->nullable()->after('foto');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active', 'foto', 'last_login']);
        });
    }
};
