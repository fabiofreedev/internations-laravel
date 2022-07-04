<?php

use App\Domain\Users\Groups\Controllers\GroupController;
use App\Domain\Users\Groups\Group;
use App\Domain\Users\Roles\Enums\UserRole;
use App\Domain\Users\User;
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
            'name' => 'Test Group',
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
                'name' => 'Test Group'
            ]
        );
});

it('tests a group deletion when the group does not contain any user', function () {
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

    $this->assertModelMissing($group);
});

it('tests a group deletion when the group contains some users', function () {
    Sanctum::actingAs(
        User::factory()
            ->hasRole([
                'role' => UserRole::ADMIN
            ])
            ->create(),
    );

    $user  = User::factory()->create();
    $group = Group::factory()->create();

    $group->users()->attach($user);

    $this->deleteJson(
        action(
            [GroupController::class, 'destroy'],
            [
                'group' => $group->id
            ]
        ),
    )
        ->assertUnprocessable()
        ->assertJson(
            [
                'error' => "The group {$group->id} cannot be deleted because it contains 1 users"
            ]
        );
});

it('tests add a user to a group of which is not already part of', function () {
    Sanctum::actingAs(
        User::factory()
            ->hasRole([
                'role' => UserRole::ADMIN
            ])
            ->create(),
    );

    $user  = User::factory()->create();
    $group = Group::factory()->create();

    $this->putJson(
        action(
            [GroupController::class, 'addUser'],
            [
                'group' => $group->id
            ]
        ),
        [
            'user_id' => $user->id
        ]
    )
        ->assertSuccessful()
        ->assertJson(
            [
                'success' => "User {$user->id} added to group {$group->id}"
            ]
        );

    $this->assertTrue($group->users->contains($user));
});

it('tests add a user to a group of which is already part of', function () {
    Sanctum::actingAs(
        User::factory()
            ->hasRole([
                'role' => UserRole::ADMIN
            ])
            ->create(),
    );

    $user  = User::factory()->create();
    $group = Group::factory()->create();

    $group->users()->attach($user);

    $this->assertTrue($group->users->contains($user));

    $this->putJson(
        action(
            [GroupController::class, 'addUser'],
            [
                'group' => $group->id
            ]
        ),
        [
            'user_id' => $user->id
        ]
    )
        ->assertUnprocessable()
        ->assertJson(
            [
                'error' => "User {$user->id} is already part of the group {$group->id}"
            ]
        );
});

it('tests remove a user from a group of which is part of', function () {
    Sanctum::actingAs(
        User::factory()
            ->hasRole([
                'role' => UserRole::ADMIN
            ])
            ->create(),
    );

    $user  = User::factory()->create();
    $group = Group::factory()->create();

    $group->users()->attach($user);

    $this->assertTrue($group->users->contains($user));

    $this->deleteJson(
        action(
            [GroupController::class, 'removeUser'],
            [
                'group' => $group->id,
                'user' => $user->id
            ]
        )
    )
        ->assertSuccessful()
        ->assertJson(
            [
                'success' => "User {$user->id} has been removed from the group {$group->id}"
            ]
        );
    $group->load('users');
    $this->assertNotTrue($group->users->contains($user));
});

it('tests remove a user from a group of which is not part of', function () {
    Sanctum::actingAs(
        User::factory()
            ->hasRole([
                'role' => UserRole::ADMIN
            ])
            ->create(),
    );

    $user  = User::factory()->create();
    $group = Group::factory()->create();

    $this->deleteJson(
        action(
            [GroupController::class, 'removeUser'],
            [
                'group' => $group->id,
                'user' => $user->id
            ]
        )
    )
        ->assertUnprocessable()
        ->assertJson(
            [
                'error' => "User {$user->id} is not part of the group {$group->id}"
            ]
        );

    $this->assertNotTrue($group->users->contains($user));
});
