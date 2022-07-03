<?php

use App\Domain\Auth\Controllers\AuthController;
use App\Domain\Users\Roles\Enums\UserRole;
use App\Domain\Users\User;

it('tests login for non-existing user', function () {
    $this->postJson(
        action([AuthController::class, 'login']),
        [
            'email'    => 'non-existing@internations.com',
            'password' => 'password'
        ]
    )
        ->assertUnauthorized();
});

it('tests login for existing user', function () {
    $user = User::factory()
        ->hasRole([
            'role' => UserRole::ADMIN
        ])
        ->create();

    $this->postJson(
        action([AuthController::class, 'login']),
        [
            'email'    => $user->email,
            'password' => 'password'
        ]
    )
        ->assertSuccessful()
        ->assertJsonStructure([
            'access_token',
            'token_type'
        ]);
});
