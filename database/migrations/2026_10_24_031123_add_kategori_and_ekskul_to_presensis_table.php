<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            if (!Schema::hasColumn('presensis', 'kategori')) {
                // Nilai: 'harian', 'shalat', atau 'ekskul'
                $table->string('kategori', 25)->default('harian')->after('tanggal');
            }
            if (!Schema::hasColumn('presensis', 'ekskul_id')) {
                $table->foreignId('ekskul_id')->nullable()->after('kategori');
            }
        });
    }

    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            if (Schema::hasColumn('presensis', 'ekskul_id')) {
                $table->dropColumn('ekskul_id');
            }
            if (Schema::hasColumn('presensis', 'kategori')) {
                $table->dropColumn('kategori');
            }
        });
    }
};