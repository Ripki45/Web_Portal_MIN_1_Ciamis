<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_unggulans', function (Blueprint $table) {
            $table->text('kegiatan_list')->nullable()->after('kegiatan_judul');
            $table->string('kegiatan_deskripsi', 500)->nullable()->after('kegiatan_badge');
        });
    }

    public function down(): void
    {
        Schema::table('program_unggulans', function (Blueprint $table) {
            $table->dropColumn(['kegiatan_list', 'kegiatan_deskripsi']);
        });
    }
};
