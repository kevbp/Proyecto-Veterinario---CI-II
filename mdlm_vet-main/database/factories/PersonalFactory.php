<?php

namespace Database\Factories;

use App\Models\Personal;
use App\Models\TipoDocumento;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PersonalFactory extends Factory
{
    protected $model = Personal::class;

    public function definition(): array
    {
        return [
            'id'           => (string) Str::uuid(),
            'user_id'      => User::factory(),
            'tipo_doc_id'  => TipoDocumento::factory(),
            'nro_doc'      => (int) $this->faker->unique()->numerify('########'),
            'nombre'       => $this->faker->firstName(),
            'paterno'      => $this->faker->lastName(),
            'materno'      => $this->faker->lastName(),
            'email'        => $this->faker->unique()->safeEmail(),
            'celular'      => $this->faker->numerify('9########'),
            'especialidad' => $this->faker->randomElement(['General', 'Cirugía', 'Cardiología', 'Dermatología']),
            'rol_sistema'  => 'veterinario',
        ];
    }
}
