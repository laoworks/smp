<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            // Sudah ada updated_at, pastikan saja
            if (!Schema::hasColumn('sliders', 'updated_at')) {
                $table->timestamps();
            }
        });
    }

    public function down(): void
    {
        //
    }
};
