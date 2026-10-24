<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_jabatan',
        'keterangan',
    ];

    // Relasi: Satu Jabatan memiliki banyak Posisi
    public function posisis()
    {
        return $this->hasMany(Posisi::class, 'jabatan_id');
    }
}