<?php

namespace Database\Factories\Domain\Users\Groups;

use App\Domain\Users\Groups\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class GroupFactory extends Factory
{
    protected $model = Group::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company
        ];
    }
}
