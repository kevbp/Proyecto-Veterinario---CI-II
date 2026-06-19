<?php

namespace Database\Factories;

use App\Models\EstadoCita;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EstadoCitaFactory extends Factory
{
    protected $model = EstadoCita::class;

    public function definition(): array
    {
        return [
            'id'        => (string) Str::uuid(),
            'codigo'    => strtoupper($this->faker->unique()->lexify('???')),
            'nombre'    => $this->faker->word(),
            'color_hex' => $this->faker->hexColor(),
        ];
    }
}
