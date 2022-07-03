<?php


use App\Domain\Users\Roles\Enums\UserRole;
use App\Domain\Users\User;

it('tests a user without role', function () {
    $user = User::factory()
        ->create();

    $this->assertFalse($user->isAdmin());
});

it('tests a user with role admin', function () {
    $user = User::factory()
        ->hasRole([
            'role' => UserRole::ADMIN
        ])
        ->create();

    $this->assertTrue($user->isAdmin());
});
