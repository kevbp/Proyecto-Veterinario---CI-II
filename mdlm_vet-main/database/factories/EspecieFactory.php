<?php

namespace Database\Factories;

use App\Models\Especie;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EspecieFactory extends Factory
{
    protected $model = Especie::class;

    public function definition(): array
    {
        return [
            'id'     => (string) Str::uuid(),
            'codigo' => strtoupper($this->faker->unique()->lexify('???')),
            'nombre' => $this->faker->randomElement(['Canino', 'Felino', 'Ave', 'Reptil', 'Roedor']),
        ];
    }
}
