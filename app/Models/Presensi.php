<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'tahun_ajaran_id',
        'tanggal',
        'kategori',    // 'harian', 'shalat', 'ekskul'
        'ekskul_id',   // ID Ekskul jika kategori = ekskul
        'waktu_masuk', // Sesuai kolom asli database kamu
        'status',
        'metode',
        'keterangan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function ekskul()
    {
        return $this->belongsTo(Ekskul::class, 'ekskul_id');
    }
}