<?php

namespace Database\Factories;

use App\Enums\EstadoCampania;
use App\Models\Campania;
use App\Models\Personal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CampaniaFactory extends Factory
{
    protected $model = Campania::class;

    public function definition(): array
    {
        $inicio = $this->faker->dateTimeBetween('+1 day', '+30 days');
        $fin = (clone $inicio)->modify('+7 days');

        return [
            'id'                => (string) Str::uuid(),
            'nombre'            => 'Campaña ' . $this->faker->word() . ' ' . $this->faker->year(),
            'descripcion'       => $this->faker->paragraph(),
            'lugar'             => $this->faker->address(),
            'fecha_hora_inicio' => $inicio->format('Y-m-d H:i:s'),
            'fecha_hora_fin'    => $fin->format('Y-m-d H:i:s'),
            'estado'            => EstadoCampania::PLANIFICADA,
            'responsable_id'    => Personal::factory(),
        ];
    }

    /**
     * Estado: en_curso
     */
    public function enCurso(): static
    {
        return $this->state(['estado' => EstadoCampania::EN_CURSO]);
    }

    /**
     * Estado: finalizada
     */
    public function finalizada(): static
    {
        return $this->state(['estado' => EstadoCampania::FINALIZADA]);
    }

    /**
     * Estado: cancelada
     */
    public function cancelada(): static
    {
        return $this->state(['estado' => EstadoCampania::CANCELADA]);
    }
}
