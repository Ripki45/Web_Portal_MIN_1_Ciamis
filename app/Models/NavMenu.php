<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NavMenu extends Model
{
    protected $table = 'nav_menus';

    protected $fillable = [
        'nama_menu',
        'url',
        'parent_id',
        'level',
        'urutan',
        'target',
        'badge_new',
        'is_active',
    ];

    protected $casts = [
        'badge_new' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Induk Menu
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Relasi ke Anak Menu (dan Cucu Menu)
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc');
    }

    /**
     * Relasi khusus yang hanya mengambil Anak/Cucu yang berstatus Aktif untuk Halaman Depan
     */
    public function activeChildren()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc');
    }

    /**
     * Pastikan tabel nav_menus tersedia di SQLite & isi menu awal jika masih kosong
     */
    public static function pastikanTabelSiap(): void
    {
        static $sudahDicek = false;
        if ($sudahDicek) {
            return;
        }
        $sudahDicek = true;

        if (!Schema::hasTable('nav_menus')) {
            Schema::create('nav_menus', function (Blueprint $table) {
                $table->id();
                $table->string('nama_menu', 100);
                $table->string('url', 255)->default('#');
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->tinyInteger('level')->default(1); // 1: Induk, 2: Anak, 3: Cucu
                $table->integer('urutan')->default(1);
                $table->string('target', 20)->default('_self'); // _self atau _blank
                $table->boolean('badge_new')->default(false); // Icon petir Magzin
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            // Seed menu awal MIN 1 Ciamis (Contoh Induk -> Anak -> Cucu)
            $beranda = self::create([
                'nama_menu' => 'Beranda',
                'url'       => '/',
                'parent_id' => null,
                'level'     => 1,
                'urutan'    => 1,
                'badge_new' => true,
                'is_active' => true,
            ]);

            $profil = self::create([
                'nama_menu' => 'Profil Madrasah',
                'url'       => '#',
                'parent_id' => null,
                'level'     => 1,
                'urutan'    => 2,
                'is_active' => true,
            ]);

            self::create([
                'nama_menu' => 'Sambutan Kepala Madrasah',
                'url'       => '#sambutan',
                'parent_id' => $profil->id,
                'level'     => 2,
                'urutan'    => 1,
                'is_active' => true,
            ]);

            $struktur = self::create([
                'nama_menu' => 'Struktur & Direktori',
                'url'       => '#',
                'parent_id' => $profil->id,
                'level'     => 2,
                'urutan'    => 2,
                'is_active' => true,
            ]);

            // Contoh Level 3 (Cucu Menu) di bawah Struktur & Direktori
            self::create([
                'nama_menu' => 'Direktori Guru & Staf',
                'url'       => '#direktori-guru',
                'parent_id' => $struktur->id,
                'level'     => 3,
                'urutan'    => 1,
                'is_active' => true,
            ]);

            self::create([
                'nama_menu' => 'Program Unggulan',
                'url'       => '#program-unggulan',
                'parent_id' => $struktur->id,
                'level'     => 3,
                'urutan'    => 2,
                'is_active' => true,
            ]);

            self::create([
                'nama_menu' => 'Berita & Artikel',
                'url'       => '#berita-terbaru',
                'parent_id' => null,
                'level'     => 1,
                'urutan'    => 3,
                'is_active' => true,
            ]);

            self::create([
                'nama_menu' => 'Ekstrakurikuler',
                'url'       => '#ekstrakurikuler',
                'parent_id' => null,
                'level'     => 1,
                'urutan'    => 4,
                'is_active' => true,
            ]);

            self::create([
                'nama_menu' => 'Galeri & Prestasi',
                'url'       => '#galeri',
                'parent_id' => null,
                'level'     => 1,
                'urutan'    => 5,
                'is_active' => true,
            ]);
        }
    }

    /**
     * Ambil struktur pohon 3 level (Induk -> Anak -> Cucu) untuk Navbar Depan
     */
    public static function getTree()
    {
        self::pastikanTabelSiap();

        return self::with(['activeChildren.activeChildren'])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }
}