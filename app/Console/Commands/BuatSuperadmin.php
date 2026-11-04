<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class BuatSuperadmin extends Command
{
    protected $signature = 'buat:superadmin';
    protected $description = 'Membuat atau mengangkat akun menjadi Superadmin';

    public function handle()
    {
        $this->info('=== SETUP AKUN SUPERADMIN MIN 1 CIAMIS ===');

        // Semuanya menggunakan ask() biasa agar huruf terlihat saat diketik di Windows
        $name     = $this->ask('1. Masukkan Nama Lengkap', 'Ripki Ramdani, S.Pd');
        $email    = $this->ask('2. Masukkan Email Login', 'admin@min1ciamis.sch.id');
        $password = $this->ask('3. Masukkan Password Baru (Huruf terlihat, min 8 karakter)', 'admin12345');

        if (strlen(trim($password)) < 8) {
            $this->error('Gagal: Password minimal harus 8 karakter!');
            return 1;
        }

        User::pastikanTabelRoleSiap();

        // Cari email jika sudah ada, atau buat baru jika belum ada
        $user = User::firstOrNew(['email' => strtolower(trim($email))]);
        $user->name     = trim($name);
        $user->password = Hash::make(trim($password));
        $user->role     = 'superadmin'; // Dikunci langsung tanpa terhalang $fillable
        $user->save();

        $this->info('--------------------------------------------------');
        $this->info(' BERHASIL! Akun Superadmin sudah aktif:');
        $this->info(' Nama     : ' . $user->name);
        $this->info(' Email    : ' . $user->email);
        $this->info(' Password : ' . trim($password));
        $this->info(' Role     : ' . $user->role);
        $this->info('--------------------------------------------------');

        return 0;
    }
}