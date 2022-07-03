<?php

namespace Database\Factories\Domain\Users\Roles;

use App\Domain\Users\Roles\Enums\UserRole;
use App\Domain\Users\Roles\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Users\Roles\Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'role' => UserRole::ADMIN
        ];
    }
}
