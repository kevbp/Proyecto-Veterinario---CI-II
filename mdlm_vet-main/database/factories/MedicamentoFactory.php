<?php

namespace Database\Factories;

use App\Models\Medicamento;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MedicamentoFactory extends Factory
{
    protected $model = Medicamento::class;

    public function definition(): array
    {
        return [
            'id'          => (string) Str::uuid(),
            'codigo'      => strtoupper($this->faker->unique()->bothify('MED-###')),
            'nombre'      => $this->faker->randomElement(['Amoxicilina', 'Ivermectina', 'Praziquantel', 'Meloxicam', 'Cefalexina']),
            'descripcion' => $this->faker->sentence(),
            'stock'       => $this->faker->randomFloat(1, 10, 500),
            'estado'      => 'activo',
            'foto'        => null,
            'user_id'     => null,
        ];
    }
}
