<?php

namespace Database\Factories;

use App\Models\Animal;
use App\Models\Especie;
use App\Models\Propietario;
use App\Models\Raza;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AnimalFactory extends Factory
{
    protected $model = Animal::class;

    public function definition(): array
    {
        return [
            'id'               => (string) Str::uuid(),
            'propietario_id'   => Propietario::factory(),
            'nombre'           => $this->faker->randomElement(['Firulais', 'Luna', 'Max', 'Bella', 'Rocky', 'Coco', 'Simba', 'Mia']),
            'especie_id'       => Especie::factory(),
            'raza_id'          => Raza::factory(),
            'sexo'             => $this->faker->randomElement(['Macho', 'Hembra']),
            'color'            => $this->faker->safeColorName(),
            'esterilizacion'   => $this->faker->boolean(50),
            'fallecido'        => false,
            'fecha_fallecimiento' => null,
        ];
    }
}
