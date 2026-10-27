<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',   // 'superadmin', 'admin', 'guru', 'pembina_ekskul'
        'gtk_id', // Relasi ke tabel gtks
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Daftar seluruh modul/fitur yang bisa diatur Toggle On/Off oleh Superadmin
     */
    public static function daftarModulSistem(): array
    {
        return [
            'kepegawaian'         => ['label' => 'Kepegawaian (Master Jabatan, Posisi & Data GTK)', 'kategori' => 'Manajemen GTK'],
            'tahun_ajaran'        => ['label' => 'Manajemen Tahun Ajaran', 'kategori' => 'Akademik & Kelas'],
            'kelas'               => ['label' => 'Manajemen Kelas & Plotting Mapel', 'kategori' => 'Akademik & Kelas'],
            'siswa'               => ['label' => 'Data Siswa, Buku Mutasi & Cetak Kartu QR', 'kategori' => 'Akademik & Kelas'],
            'mapel'               => ['label' => 'Master Mata Pelajaran', 'kategori' => 'Akademik & Kelas'],
            'jadwal'              => ['label' => 'Jadwal Pelajaran', 'kategori' => 'Akademik & Kelas'],
            'ekskul'              => ['label' => 'Manajemen & Anggota Ekstrakurikuler', 'kategori' => 'Akademik & Kelas'],
            'kenaikan_reset'      => ['label' => 'Kenaikan Kelas, Alumni & Reset Sistem', 'kategori' => 'Akademik & Kelas'],
            'presensi_scanner'    => ['label' => 'Layar Scan Barcode QR (Harian/Shalat/Ekskul)', 'kategori' => 'Presensi Harian'],
            'presensi_monitoring' => ['label' => 'Presensi Kelas Harian & Pengaturan Jam', 'kategori' => 'Presensi Harian'],
            'presensi_rekap'      => ['label' => 'Rekap & Laporan Bulanan (PDF & Excel)', 'kategori' => 'Presensi Harian'],
            'cms'                 => ['label' => 'Konten Website Publik (Menu, Berita, Galeri)', 'kategori' => 'Konten Website'],
            'pengaturan'          => ['label' => 'Pengaturan Identitas Madrasah', 'kategori' => 'Pengaturan'],
        ];
    }

    /**
     * Otomatis memastikan kolom role, gtk_id, dan tabel role_permissions tersedia secara terpisah
     */
    public static function pastikanTabelRoleSiap(): void
    {
        // 1. Cek kolom role secara mandiri
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role', 30)->default('superadmin')->nullable();
            });
            self::query()->whereNull('role')->update(['role' => 'superadmin']);
        }

        // 2. Cek kolom gtk_id secara mandiri (Agar tetap dibuat meskipun kolom role sudah ada duluan)
        if (!Schema::hasColumn('users', 'gtk_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('gtk_id')->nullable();
            });
        }

        // 3. Cek tabel role_permissions untuk matriks Toggle Switch
        if (!Schema::hasTable('role_permissions')) {
            Schema::create('role_permissions', function (Blueprint $table) {
                $table->id();
                $table->string('role', 30);
                $table->string('menu_key', 50);
                $table->boolean('is_allowed')->default(false);
                $table->timestamps();
                $table->unique(['role', 'menu_key']);
            });

            $defaults = [
                'admin' => [
                    'kepegawaian' => true, 'tahun_ajaran' => true, 'kelas' => true, 'siswa' => true,
                    'mapel' => true, 'jadwal' => true, 'ekskul' => true, 'presensi_scanner' => true,
                    'presensi_monitoring' => true, 'presensi_rekap' => true, 'kenaikan_reset' => true,
                ],
                'guru' => [
                    'siswa' => true, 'jadwal' => true, 'presensi_scanner' => true,
                    'presensi_monitoring' => true, 'presensi_rekap' => true,
                ],
                'pembina_ekskul' => [
                    'ekskul' => true, 'presensi_scanner' => true, 'presensi_rekap' => true,
                ],
            ];

            foreach (['admin', 'guru', 'pembina_ekskul'] as $r) {
                foreach (array_keys(self::daftarModulSistem()) as $key) {
                    DB::table('role_permissions')->insert([
                        'role'       => $r,
                        'menu_key'   => $key,
                        'is_allowed' => $defaults[$r][$key] ?? false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function gtk()
    {
        return $this->belongsTo(Gtk::class, 'gtk_id');
    }

    public function hasRole(array|string $roles): bool
    {
        self::pastikanTabelRoleSiap();
        $userRole = $this->role ?: 'superadmin';
        if ($userRole === 'superadmin') {
            return true;
        }
        $rolesArray = is_array($roles) ? $roles : explode(',', $roles);
        return in_array($userRole, array_map('trim', $rolesArray));
    }

    /**
     * Mengecek apakah user boleh mengakses suatu modul berdasarkan Toggle Switch Superadmin
     */
    public function canAccess(string $menuKey): bool
    {
        self::pastikanTabelRoleSiap();
        $userRole = $this->role ?: 'superadmin';

        if ($userRole === 'superadmin') {
            return true;
        }

        $izin = DB::table('role_permissions')
            ->where('role', $userRole)
            ->where('menu_key', $menuKey)
            ->value('is_allowed');

        return (bool) $izin;
    }

    public function getLabelRoleAttribute(): string
    {
        return match ($this->role) {
            'superadmin'     => 'Super Admin',
            'admin'          => 'Admin / Operator TU',
            'guru'           => 'Guru (Wali Kelas / Mapel)',
            'pembina_ekskul' => 'Pembina Ekstrakurikuler',
            default          => 'Super Admin',
        };
    }
    
}