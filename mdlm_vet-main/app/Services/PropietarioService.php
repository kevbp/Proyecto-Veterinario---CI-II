<?php

namespace App\Services;

use App\DTOs\Propietario\CreatePropietarioDTO;
use App\DTOs\Propietario\UpdatePropietarioDTO;
use App\Models\Propietario;
use App\Models\TipoDocumento;
use App\Services\Contracts\PropietarioServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PropietarioService implements PropietarioServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array
    {
        return Propietario::with('tipoDocumento')->latest()->paginate(25);
    }

    public function findById(string $id): Propietario
    {
        return Propietario::findOrFail($id);
    }

    /**
     * Crea el propietario en la BD local.
     *
     * No se genera invitación ni token. El personal de ventanilla debe indicarle
     * verbalmente al vecino que se registre en el SSO-IAM para poder acceder
     * a los registros de su mascota por la web.
     */
    public function create(CreatePropietarioDTO $dto): Propietario
    {
        $data = $dto->toArray();
        
        // Convertir codigo a tipo_doc_id
        if (isset($data['tipo_doc'])) {
            $tipoDocumento = TipoDocumento::where('codigo', $data['tipo_doc'])->firstOrFail();
            $data['tipo_doc_id'] = $tipoDocumento->id;
            unset($data['tipo_doc']);
        }

        return Propietario::create($data);
    }

    public function update(string $id, UpdatePropietarioDTO $dto): Propietario
    {
        $propietario = $this->findById($id);
        $data = $dto->toArray();
        
        // Convertir codigo a tipo_doc_id
        if (isset($data['tipo_doc'])) {
            $tipoDocumento = TipoDocumento::where('codigo', $data['tipo_doc'])->firstOrFail();
            $data['tipo_doc_id'] = $tipoDocumento->id;
            unset($data['tipo_doc']);
        }

        $propietario->update($data);
        return $propietario;
    }

    public function delete(string $id): bool
    {
        $propietario = $this->findById($id);
        return $propietario->delete();
    }

    /**
     * Obtener la dirección de vivienda de un propietario.
     */
    public function obtenerDireccion(string $id): ?array
    {
        $propietario = $this->findById($id);

        if (!$propietario->vivienda_direccion && !$propietario->vivienda_latitud) {
            return null;
        }

        return [
            'direccion' => $propietario->vivienda_direccion,
            'latitud' => $propietario->vivienda_latitud,
            'longitud' => $propietario->vivienda_longitud,
        ];
    }

    /**
     * Retorna la URL del SSO-IAM donde el vecino debe registrarse.
     */
    public function getSsoRegistroUrl(): string
    {
        $baseUrl = rtrim(config('sso.url', 'http://sso.test'), '/');
        $registerPath = config('sso.jwks_path', '/.well-known/jwks.json');

        return $baseUrl . $registerPath;
    }
}
