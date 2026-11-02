<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Mapel extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function getTable()
    {
        if (Schema::hasTable('mapels')) {
            return 'mapels';
        }
        if (Schema::hasTable('mata_pelajarans')) {
            return 'mata_pelajarans';
        }
        return parent::getTable();
    }

    public function kelasMapels()
    {
        return $this->hasMany(KelasMapel::class, 'mapel_id');
    }
}