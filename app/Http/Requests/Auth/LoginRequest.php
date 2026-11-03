<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email', 'max:150'],
            'password' => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * Proses autentikasi dengan proteksi Brute-Force ketat
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // Kunci selama 120 detik (2 menit) jika gagal 4 kali berturut-turut
            RateLimiter::hit($this->throttleKey(), 120);

            // Pesan disamarkan agar penyerang tidak tahu apakah email terdaftar atau tidak
            throw ValidationException::withMessages([
                'email' => 'Kombinasi email atau kata sandi yang Anda masukkan tidak valid.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Maksimal 4 kali percobaan gagal per kombinasi Email + IP Address
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 4)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => 'Terlalu banyak percobaan login gagal. Demi keamanan, akses dikunci sementara. Silakan coba lagi dalam ' . $seconds . ' detik.',
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}