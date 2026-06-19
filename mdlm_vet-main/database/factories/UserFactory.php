<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id'     => (string) Str::uuid(),
            'sso_id' => (string) Str::uuid(),
            'type'   => 'local',
            'name'   => fake()->name(),
            'email'  => fake()->unique()->safeEmail(),
            'phone'  => fake()->numerify('9########'),
        ];
    }
}
