<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'nomor_induk',
        'nisn',
        'nama_siswa',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'nama_ayah',
        'nama_ibu',
        'alamat_ortu',
        'pekerjaan_ortu',
        'foto',
        'status',
        'kelas_terakhir', // <-- Tambahan untuk Arsip Alumni
        'tahun_lulus',    // <-- Tambahan untuk Arsip Alumni
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'siswa_id');
    }
}