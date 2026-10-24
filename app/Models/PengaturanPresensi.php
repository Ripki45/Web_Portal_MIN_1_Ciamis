<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanPresensi extends Model
{
    protected $fillable = [
        'jam_buka',
        'jam_tutup',
        'jam_tutup_manual',
        'pin_akses',
        'libur_minggu',
    ];
}