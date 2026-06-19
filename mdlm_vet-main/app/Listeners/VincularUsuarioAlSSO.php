<?php

namespace App\Listeners;

use App\Models\Propietario;
use App\Models\Personal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Listener del evento 'sso.user.authenticated'.
 *
 * Se ejecuta en cada petición autenticada vía SSO. Su responsabilidad es:
 *
 * CASO VECINO:
 *   Buscar si el DNI del JWT coincide con algún propietario sin vincular.
 *   Si lo encuentra, vincula el propietario → user y asigna rol 'propietario'.
 *
 * CASO FUNCIONARIO:
 *   Buscar si el DNI del JWT coincide con algún personal sin vincular.
 *   Si lo encuentra, vincula el personal → user.
 *   Sincroniza el rol del IAM con el sistema local.
 */
class VincularUsuarioAlSSO
{
    public function handle(User $user, array $payload, bool $isNewUser): void
    {
        $docNumber = $payload['doc_number'] ?? null;
        $userType = $payload['type']        ?? null;
        $iamRole = $payload['iam_role']     ?? null;

        if (!$docNumber || !$userType) return; 

        DB::transaction(function () use ($user, $userType, $docNumber, $iamRole, $isNewUser, $payload) {
            
            // --- Caso A: Es un Vecino ---
            if ($userType === 'vecino') {
                $this->vincularVecino($user, $docNumber);
            }
            
            // --- Caso B: Es un Funcionario ---
            elseif ($userType === 'funcionario') {
                $this->vincularFuncionario($user, $docNumber, $iamRole, $payload);
            }
            
        });
    }

    /**
     * Vincula un vecino con su registro de propietario (si existe y no está vinculado).
     *
     * Flujo:
     * 1. Vecino se registra en el SSO-IAM por su cuenta.
     * 2. Al acceder a la Veterinaria, el SsoJwtGuard crea el User local.
     * 3. Este método busca si el DNI del JWT coincide con un propietario existente.
     * 4. Si coincide → vincula propietario.user_id y asigna rol 'propietario'.
     * 5. Si no coincide → no se asigna rol (el vecino no es propietario en este sistema).
     */
    private function vincularVecino(User $user, string|int $docNumber): void
    {
        // Si el user ya tiene propietario vinculado, no hacer nada
        $yaVinculado = Propietario::where('user_id', $user->id)->exists();
        if ($yaVinculado) {
            // Asegurar que tenga el rol correcto
            $user->loadMissing('roles');
            if (!$user->hasRole('propietario')) {
                $user->syncRoles(['propietario']);
            }
            return;
        }

        // Buscar propietario por DNI sin vincular
        $propietario = Propietario::where('nro_doc', $docNumber)
            ->whereNull('user_id')
            ->first();

        if ($propietario) {
            $propietario->update(['user_id' => $user->id]);

            $user->loadMissing('roles');
            if (!$user->hasRole('propietario')) {
                $user->syncRoles(['propietario']);
            }

            Log::info("Vecino vinculado como propietario", [
                'user_id' => $user->id,
                'propietario_id' => $propietario->id,
                'nro_doc' => $docNumber,
            ]);
        } else {
            Log::info("Vecino autenticado sin registro de propietario", [
                'user_id' => $user->id,
                'nro_doc' => $docNumber,
            ]);
        }
    }

    /**
     * Vincula un funcionario con su registro de personal (si existe y no está vinculado).
     * Si no existe, lo crea automáticamente basado en los datos del SSO.
     * Sincroniza el rol del IAM con el sistema local.
     */
    private function vincularFuncionario(User $user, string|int $docNumber, ?string $iamRole, array $payload = []): void
    {
        // Vincular con tabla personal si aún no está vinculado
        $personal = Personal::where('nro_doc', $docNumber)
            ->first();

        if ($personal && !$personal->user_id) {
            $personal->update(['user_id' => $user->id]);

            Log::info("Funcionario vinculado con registro de personal existente", [
                'user_id' => $user->id,
                'personal_id' => $personal->id,
                'nro_doc' => $docNumber,
            ]);
        } elseif (!$personal) {
            // Crear el registro de personal si no existe
            $tipoDocDNI = \App\Models\TipoDocumento::where('codigo', 'DNI')->first();
            
            $fullName = $payload['full_name'] ?? ($payload['first_name'] ?? 'Usuario SSO');
            $parts = explode(' ', trim($fullName));
            $materno = count($parts) > 2 ? array_pop($parts) : null;
            $paterno = count($parts) > 1 ? array_pop($parts) : 'Sin Apellido';
            $nombre = implode(' ', $parts);
            if (empty($nombre)) $nombre = 'Sin Nombre';

            $personal = Personal::create([
                'user_id' => $user->id,
                'tipo_doc_id' => $tipoDocDNI?->id, // Asumimos DNI por defecto
                'nro_doc' => $docNumber,
                'nombre' => $nombre,
                'paterno' => $paterno,
                'materno' => $materno,
                'email' => $payload['email'] ?? $user->email,
                'rol_sistema' => $iamRole ?? 'veterinario',
            ]);

            Log::info("Registro de personal creado y vinculado automáticamente", [
                'user_id' => $user->id,
                'personal_id' => $personal->id,
                'nro_doc' => $docNumber,
            ]);
        }

        // Sincronizar rol del IAM
        $user->loadMissing('roles');
        $rolActual = $user->roles->first()?->name;

        if ($rolActual !== $iamRole) {
            if ($iamRole) {
                $user->syncRoles([$iamRole]);
            } else {
                $user->syncRoles([]);  // funcionario sin rol asignado en el SSO
            }
        }
    }
}