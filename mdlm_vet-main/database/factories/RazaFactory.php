<?php

namespace Database\Factories;

use App\Models\Especie;
use App\Models\Raza;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RazaFactory extends Factory
{
    protected $model = Raza::class;

    public function definition(): array
    {
        return [
            'id'         => (string) Str::uuid(),
            'nombre'     => $this->faker->word() . ' ' . $this->faker->lexify('???'),
            'codigo'     => strtoupper($this->faker->unique()->lexify('???###')),
            'peligroso'  => $this->faker->boolean(20),
            'especie_id' => Especie::factory(),
        ];
    }
}
