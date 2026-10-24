<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiSiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'tahun_ajaran_id',
        'jenis_mutasi',
        'kelas_asal',
        'diterima_di_kelas_id',
        'sekolah_asal_tujuan',
        'alamat_sekolah',
        'tanggal_mutasi',
        'alasan_mutasi',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function kelasTujuan()
    {
        return $this->belongsTo(Kelas::class, 'diterima_di_kelas_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}