<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekskul extends Model
{
    use HasFactory;

    protected $table = 'ekskuls';

    protected $fillable = [
        'nama_ekskul',
        'pembina_id',
        'hari_latihan',
        'jam_latihan',
        'status',
    ];

    public function pembina()
    {
        return $this->belongsTo(Gtk::class, 'pembina_id');
    }

    public function anggotas()
    {
        return $this->hasMany(EkskulAnggota::class, 'ekskul_id');
    }
}