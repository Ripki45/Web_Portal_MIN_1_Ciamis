<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengaturan_presensis', function (Blueprint $table) {
            $table->time('jam_tutup_manual')->default('14:00:00')->after('jam_tutup');
            $table->string('pin_akses', 10)->default('1234')->after('jam_tutup_manual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturan_presensis', function (Blueprint $table) {
            $table->dropColumn(['jam_tutup_manual', 'pin_akses']);
        });
    }
};
