<?php

use App\Domain\Users\Controllers\UserController;
use App\Domain\Users\Roles\Enums\UserRole;
use App\Domain\Users\User;
use Laravel\Sanctum\Sanctum;

it('tests users routes are protected from non-logged users', function ($method, $route, $id) {
    $this->json($method, action([UserController::class, $route], $id))->assertUnauthorized();
})->with([
    ['GET', 'index', []],
    ['POST', 'store', []],
    ['DELETE', 'destroy', ['user' => 1]]
]);

it('tests a route accessible only for admin users is unauthorized for non-admin users', function ($method, $route, $id) {
    Sanctum::actingAs(
        User::factory()->create(),
    );

    $this->json($method, action([UserController::class, $route], $id))->assertUnauthorized();
})->with([
    ['GET', 'index', []],
    ['POST', 'store', []],
    ['DELETE', 'destroy', ['user' => 1]]
]);

it('tests index route is accessible to an admin user', function () {
    Sanctum::actingAs(
        User::factory()
            ->hasRole([
                'role' => UserRole::ADMIN
            ])
            ->create(),
    );

    $this->getJson(action([UserController::class, 'index']))->assertSuccessful();
});

it('tests validation errors for store route', function () {
    Sanctum::actingAs(
        User::factory()
            ->hasRole([
                'role' => UserRole::ADMIN
            ])
            ->create(),
    );

    $this->postJson(action([UserController::class, 'store']), [])->assertJsonValidationErrors(['email', 'name', 'password']);
});
