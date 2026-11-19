<?php

use App\Models\User;

test('email verification screen cannot be rendered because email verification is disabled', function () {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->get('/verify-email');

    $response->assertStatus(404);
});

test('email can be verified', function () {
    // Skipped: sistem akun MIN 1 Ciamis dikelola penuh oleh Super Admin dan tidak menggunakan rute verifikasi publik.
})->skip('Email verification is not enabled in this application.');

test('email is not verified with invalid hash', function () {
    // Skipped: sistem akun MIN 1 Ciamis dikelola penuh oleh Super Admin dan tidak menggunakan rute verifikasi publik.
})->skip('Email verification is not enabled in this application.');
