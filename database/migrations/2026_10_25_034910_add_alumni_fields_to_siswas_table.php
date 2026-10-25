<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            // Ubah kelas_id agar boleh kosong (NULL) saat siswa sudah Lulus menjadi Alumni
            $table->foreignId('kelas_id')->nullable()->change();

            if (!Schema::hasColumn('siswas', 'kelas_terakhir')) {
                $table->string('kelas_terakhir', 50)->nullable()->after('status');
            }
            if (!Schema::hasColumn('siswas', 'tahun_lulus')) {
                $table->string('tahun_lulus', 30)->nullable()->after('kelas_terakhir');
            }
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            if (Schema::hasColumn('siswas', 'tahun_lulus')) {
                $table->dropColumn('tahun_lulus');
            }
            if (Schema::hasColumn('siswas', 'kelas_terakhir')) {
                $table->dropColumn('kelas_terakhir');
            }
        });
    }
};