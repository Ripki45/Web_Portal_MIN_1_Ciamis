<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gtk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_induk',
        'nama_lengkap',
        'jenis_kelamin',
        'jabatan_id',
        'posisi_id', // <-- Kolom relasi ke Master Posisi
        'status_kepegawaian',
        'no_hp',
        'alamat',
        'foto',
        'ttd',
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function posisi()
    {
        return $this->belongsTo(Posisi::class, 'posisi_id');
    }

    public function kelasWali()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas_id');
    }
}