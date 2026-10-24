<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EkskulAnggota extends Model
{
    use HasFactory;

    protected $table = 'ekskul_anggotas';

    protected $fillable = [
        'ekskul_id',
        'siswa_id',
    ];

    public function ekskul()
    {
        return $this->belongsTo(Ekskul::class, 'ekskul_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}