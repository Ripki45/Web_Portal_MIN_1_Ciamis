<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KaldikDokumen extends Model
{
    protected $table = 'kaldik_dokumens';

    protected $fillable = [
        'tahun_ajaran',
        'dasar_hukum',
        'hari_kerja',
        'file_pdf',
        'tanggal_pengesahan',
        'nama_kepsek',
        'nip_kepsek',
        'catatan_kaki',
    ];

    protected $casts = [
        'hari_kerja' => 'integer',
        'tanggal_pengesahan' => 'date',
    ];

    public function getFilePdfUrlAttribute(): ?string
    {
        if (empty($this->file_pdf)) {
            return null;
        }

        return asset('storage/'.$this->file_pdf);
    }
}