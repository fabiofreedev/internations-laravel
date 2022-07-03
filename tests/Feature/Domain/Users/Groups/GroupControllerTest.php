<?php

use App\Domain\Users\Groups\Controllers\GroupController;
use App\Domain\Users\Groups\Group;
use App\Domain\Users\Roles\Enums\UserRole;
use App\Domain\Users\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Sanctum\Sanctum;

it('tests groups routes are protected from non-logged users', function ($method, $route, $id) {
    $this->json($method, action([GroupController::class, $route], $id))
        ->assertUnauthorized();
})->with([
    ['GET', 'index', []],
]);

it('tests a route accessible only for admin users is protected from non-admin users', function ($method, $route, $id) {
    Sanctum::actingAs(
        User::factory()->create(),
    );

    $this->json($method, action([GroupController::class, $route], $id))
        ->assertUnauthorized();
})->with([
    ['GET', 'index', []],
]);

it('tests index route is accessible to an admin user', function () {
    Sanctum::actingAs(
        User::factory()
            ->hasRole([
                'role' => UserRole::ADMIN
            ])
            ->create(),
    );

    $this->getJson(action([GroupController::class, 'index']))
        ->assertSuccessful();
});

it('tests validation errors for store route', function () {
    Sanctum::actingAs(
        User::factory()
            ->hasRole([
                'role' => UserRole::ADMIN
            ])
            ->create(),
    );

    $this->postJson(action([GroupController::class, 'store']), [])
        ->assertJsonValidationErrors(['name']);
});

it('tests successful group stored', function () {
    Sanctum::actingAs(
        User::factory()
            ->hasRole([
                'role' => UserRole::ADMIN
            ])
            ->create(),
    );

    $this->postJson(
        action([GroupController::class, 'store']),
        [
            'name'     => 'Test Group',
        ]
    )
        ->assertSuccessful()
        ->assertJsonStructure(
            [
                'name',
                'id'
            ]
        )
        ->assertJsonFragment(
            [
                'name'  => 'Test Group'
            ]
        );
});

it('tests a user deletion', function () {
    Sanctum::actingAs(
        User::factory()
            ->hasRole([
                'role' => UserRole::ADMIN
            ])
            ->create(),
    );

    $group = Group::factory()->create();

    $this->deleteJson(
        action(
            [GroupController::class, 'destroy'],
            [
                'group' => $group->id
            ]
        ),
    )
        ->assertNoContent();

    $this->expectException(ModelNotFoundException::class);

    Group::findOrFail($group->id);
});
