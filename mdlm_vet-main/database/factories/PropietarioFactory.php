<?php

namespace Database\Factories;

use App\Models\Propietario;
use App\Models\TipoDocumento;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PropietarioFactory extends Factory
{
    protected $model = Propietario::class;

    public function definition(): array
    {
        return [
            'id'                  => (string) Str::uuid(),
            'user_id'             => null,
            'tipo_doc_id'         => TipoDocumento::factory(),
            'nro_doc'             => (int) $this->faker->unique()->numerify('########'),
            'nombre'              => $this->faker->firstName(),
            'paterno'             => $this->faker->lastName(),
            'materno'             => $this->faker->lastName(),
            'email'               => $this->faker->unique()->safeEmail(),
            'celular'             => $this->faker->numerify('9########'),
            'nro_emergencia'      => $this->faker->numerify('9########'),
            'vivienda_direccion'  => $this->faker->address(),
            'vivienda_latitud'    => $this->faker->latitude(-12.1, -12.0),
            'vivienda_longitud'   => $this->faker->longitude(-77.0, -76.9),
        ];
    }
}
