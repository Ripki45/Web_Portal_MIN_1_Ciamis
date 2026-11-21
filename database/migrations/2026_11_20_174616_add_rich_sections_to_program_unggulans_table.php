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
        Schema::table('program_unggulans', function (Blueprint $table) {
            $table->text('mini_features')->nullable()->after('kutipan');
            $table->text('pilar_list')->nullable()->after('keunggulan_poin');
            $table->string('kegiatan_badge', 100)->nullable()->after('pilar_list');
            $table->string('kegiatan_judul', 220)->nullable()->after('kegiatan_badge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_unggulans', function (Blueprint $table) {
            $table->dropColumn(['mini_features', 'pilar_list', 'kegiatan_badge', 'kegiatan_judul']);
        });
    }
};
