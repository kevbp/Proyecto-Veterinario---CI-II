<?php

namespace Database\Factories;

use App\Models\TipoDocumento;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TipoDocumentoFactory extends Factory
{
    protected $model = TipoDocumento::class;

    public function definition(): array
    {
        return [
            'id'     => (string) Str::uuid(),
            'codigo' => strtoupper($this->faker->unique()->lexify('???')),
            'nombre' => $this->faker->words(3, true),
        ];
    }
}
