<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajarans';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'kelompok',
        'tingkat',
        'kkm',
    ];

    public function kelasMapels()
    {
        return $this->hasMany(KelasMapel::class, 'mapel_id');
    }
}