<?php

test('registration screen cannot be accessed because public registration is disabled', function () {
    $response = $this->get('/register');

    $response->assertStatus(404);
});

test('new users cannot register via public endpoint', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(404);
    $this->assertGuest();
});
