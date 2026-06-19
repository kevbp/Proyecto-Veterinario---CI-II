<?php

namespace App\Services;

use App\Models\Animal;
use App\Models\Adopcion;
use App\DTOs\Adopcion\EstadisticaCampaniaDTO;
use App\DTOs\Adopcion\EstadisticaFechasDTO;
use App\DTOs\Animal\RegistrarAdopcionDTO;
use App\Services\Contracts\AdopcionServiceInterface;
use Illuminate\Support\Facades\DB;
use Exception;

class AdopcionService implements AdopcionServiceInterface
{
    public function registrarAdopcion(RegistrarAdopcionDTO $dto): Adopcion
    {
        return DB::transaction(function () use ($dto) {
            // 1. El Servicio descubre al dueño actual (No confiamos en el Frontend para esto)
            $animal = Animal::findOrFail($dto->animal_id);
            $propietarioAnteriorId = $animal->propietario_id;

            // 2. Validar regla de negocio vital
            if ($propietarioAnteriorId === $dto->propietario_nuevo_id) {
                throw new Exception("El nuevo dueño no puede ser el mismo que el actual.");
            }

            // 3. Crear el registro histórico
            $adopcion = Adopcion::create([
                'animal_id'               => $animal->id,
                'propietario_anterior_id' => $propietarioAnteriorId,
                'propietario_nuevo_id'    => $dto->propietario_nuevo_id,
                'fecha_adopcion'          => now(),
                'observaciones'           => $dto->observaciones,
                'campania_id'             => $dto->campania_id,
            ]);

            // 4. ¡Actualizar al animal! (La transferencia real de propiedad)
            $animal->update([
                'propietario_id' => $dto->propietario_nuevo_id
            ]);

            return $adopcion;
        });
    }

    public function getAll(array $filters = []): \Illuminate\Support\Collection|\Illuminate\Pagination\LengthAwarePaginator
    {
        $query = Adopcion::with(['animal.especie', 'animal.raza', 'propietarioAnterior', 'propietarioNuevo', 'campania']);

        if (!empty($filters['campania_id'])) {
            $query->where('campania_id', $filters['campania_id']);
        }

        if (!empty($filters['fecha_inicio']) && !empty($filters['fecha_fin'])) {
            $query->whereBetween('fecha_adopcion', [$filters['fecha_inicio'], $filters['fecha_fin']]);
        }

        return $query->latest('fecha_adopcion')->paginate(25);
    }

    public function obtenerEstadisticasCampania(EstadisticaCampaniaDTO $dto): array
    {
        $query = Adopcion::where('campania_id', $dto->campania_id);

        $total = $query->count();
        
        $porEspecie = (clone $query) // Clonamos para no afectar la consulta base
            ->join('animals', 'adopcions.animal_id', '=', 'animals.id')
            ->join('especies', 'animals.especie_id', '=', 'especies.id')
            ->selectRaw('especies.nombre as especie, count(*) as total')
            ->groupBy('especies.nombre')
            ->get();

        return [
            'total_adopciones' => $total,
            'desglose_especies' => $porEspecie
        ];
    }

    public function obtenerEstadisticasFechas(EstadisticaFechasDTO $dto): array
    {
        $query = Adopcion::whereBetween('fecha_adopcion', [$dto->fecha_inicio, $dto->fecha_fin]);

        $total = $query->count();

        $porEspecie = (clone $query)
            ->join('animals', 'adopcions.animal_id', '=', 'animals.id')
            ->join('especies', 'animals.especie_id', '=', 'especies.id')
            ->selectRaw('especies.nombre as especie, count(*) as total')
            ->groupBy('especies.nombre')
            ->get();

        return [
            'total_adopciones' => $total,
            'desglose_especies' => $porEspecie
        ];
    }
}
