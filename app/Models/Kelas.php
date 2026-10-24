<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    // Mengunci nama tabel agar tetap 'kelas'
    protected $table = 'kelas';

    protected $fillable = [
        'tingkat',
        'nama_kelas',
        'wali_kelas_id',
        'ruangan',
        'kapasitas',
    ];

    // Relasi ke tabel GTK (Wali Kelas)
    public function waliKelas()
    {
        return $this->belongsTo(Gtk::class, 'wali_kelas_id');
    }

    // Relasi ke tabel Siswa (Menghitung jumlah siswa di kelas ini)
    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    public function kelasMapels()
    {
        return $this->hasMany(KelasMapel::class, 'kelas_id');
    }
}