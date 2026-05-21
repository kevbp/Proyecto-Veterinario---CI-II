<?php

namespace App\Services;

use App\DTOs\Campania\CreateCampaniaDTO;
use App\DTOs\Campania\UpdateCampaniaDTO;
use App\DTOs\Campania\FinalizarCampaniaDTO;
use App\Models\Campania;
use App\Enums\EstadoCampania;
use App\Enums\TipoMovimientoInventario;
use Exception;
use App\Models\Desparasitacion;
use App\Models\VacunaAnimal;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Contracts\InventarioServiceInterface;
use App\Services\Contracts\CampaniaServiceInterface;

class CampaniaService implements CampaniaServiceInterface
{
    public function __construct(private InventarioServiceInterface $inventarioService){}

    public function getAll(): Collection
    {
        return Campania::orderBy('fecha_hora_inicio', 'desc')->get();
    }

    public function obtenerCampaniasActivas(): Collection
    {
        return Campania::query()
                    ->whereIn('estado', ['planificada', 'en_curso'])
                    ->orderBy('fecha_hora_inicio', 'asc')
                    ->get();
    }

    public function getById(string $id): Campania
    {
        return Campania::findOrFail($id);
    }

    public function create(CreateCampaniaDTO $dto): Campania
    {
        $data = $dto->toArray();
        
        $campania = Campania::create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'lugar' => $data['lugar'],
            'fecha_hora_inicio' => $data['fecha_hora_inicio'] ?? now()->toDateTimeString(), // Si no se proporciona, se asigna la fecha actual
            'fecha_hora_fin' => $data['fecha_hora_fin'],
            'responsable_id' => $data['responsable_id'],
            'estado' => EstadoCampania::PLANIFICADA, // Siempre inicia como planificada
        ]);
        return $campania;
    }

    public function update(string $id, UpdateCampaniaDTO $dto): Campania
    {
        $campania = $this->getById($id);

        // Solo permitimos editar detalles fuertes si aún no ha finalizado
        if ($campania->estado === EstadoCampania::FINALIZADA || $campania->estado === EstadoCampania::CANCELADA) {
            throw new Exception("No se puede editar una campaña que ya ha finalizado o ha sido cancelada.");
        }

        $campania->update([
            'nombre' => $dto->nombre,
            'descripcion' => $dto->descripcion,
            'lugar' => $dto->lugar,
            'fecha_hora_inicio' => $dto->fecha_hora_inicio,
            'fecha_hora_fin' => $dto->fecha_hora_fin,
            'responsable_id' => $dto->responsable_id,
            'estado' => $dto->estado,
        ]);

        return $campania->fresh();
    }

    public function delete(string $id): void
    {
        $campania = $this->getById($id);

        // Seguridad: Evitar borrar historial médico accidentalmente
        if ($campania->estado !== EstadoCampania::PLANIFICADA) {
            throw new Exception("Solo se pueden eliminar campañas en estado 'Planificada'. Si el evento ya inició, debes cancelarlo o finalizarlo.");
        }

        $campania->delete();
    }

    public function iniciarCampania(string $id): Campania
    {
        $campania = $this->getById($id);

        if ($campania->estado !== EstadoCampania::PLANIFICADA) {
            throw new Exception("La campaña no se puede iniciar porque su estado actual es: {$campania->estado}");
        }

        $campania->update(['estado' => EstadoCampania::EN_CURSO]);

        return $campania;
    }

    public function finalizarCampania(string $id, FinalizarCampaniaDTO $dto): Campania
    {
        return DB::transaction(function () use ($id, $dto) {
            $campania = $this->getById($id);

            if ($campania->estado !== EstadoCampania::EN_CURSO) {
                throw new Exception("Solo se pueden finalizar campañas que están 'En Progreso'.");
            }

            // 1. Descargo Masivo de Inventario
            // El DTO debe traer un array: [['medicamento_id' => '...', 'cantidad' => 50], ...]
            foreach ($dto->insumos_consumidos as $insumo) {
                $this->inventarioService->registrarMovimiento(
                    medicamento_id: $insumo['medicamento_id'],
                    cantidad: $insumo['cantidad'],
                    tipo: TipoMovimientoInventario::SALIDA,
                    motivo: "Descargo general de campaña",
                    personal_id: auth('api')->user()->personal->id, // O pasarlo en el DTO
                    referencia: $campania
                );
            }

            // 2. Cerramos el evento
            $campania->update(['estado' => EstadoCampania::FINALIZADA]);

            return $campania;
        });
    }

    public function cancelarCampania(string $id): Campania
    {
        $campania = $this->getById($id);

        if ($campania->estado === EstadoCampania::FINALIZADA) {
            throw new Exception("No se puede cancelar una campaña que ya finalizó exitosamente.");
        }

        $campania->update(['estado' => EstadoCampania::CANCELADA]);

        return $campania;
    }

    public function obtenerEstadisticas(string $id): array
    {
        // Verificamos que exista
        $this->getById($id);

        // Usamos count() directamente a la BD para no sobrecargar la RAM del servidor
        // sacando miles de modelos a memoria.
        $totalVacunas = VacunaAnimal::where('campania_id', $id)->count();
        $totalDesparasitaciones = Desparasitacion::where('campania_id', $id)->count();

        // Agrupación avanzada con Eloquent (Join a la tabla animales)
        $vacunasPorEspecie = VacunaAnimal::where('campania_id', $id)
            ->join('animals', 'vacuna_animals.animal_id', '=', 'animals.id')
            ->join('especies', 'animals.especie_id', '=', 'especies.id')
            ->selectRaw('especies.nombre as especie, COUNT(*) as total')
            ->groupBy('especies.nombre')
            ->pluck('total', 'especie')
            ->toArray();

        $desparasitacionesPorEspecie = Desparasitacion::where('campania_id', $id)
            ->join('animals', 'desparasitaciones.animal_id', '=', 'animals.id')
            ->join('especies', 'animals.especie_id', '=', 'especies.id')
            ->selectRaw('especies.nombre as especie, COUNT(*) as total')
            ->groupBy('especies.nombre')
            ->pluck('total', 'especie')
            ->toArray();

        return [
            'campania_id' => $id,
            'resumen_general' => [
                'total_vacunas_aplicadas' => $totalVacunas,
                'total_desparasitaciones_aplicadas' => $totalDesparasitaciones,
                'total_intervenciones' => $totalVacunas + $totalDesparasitaciones,
            ],
            'desglose_vacunas' => $vacunasPorEspecie,
            'desglose_desparasitaciones' => $desparasitacionesPorEspecie,
        ];
    }
}
