<?php

namespace App\Services;

use App\DTOs\Propietario\CreatePropietarioDTO;
use App\DTOs\Propietario\UpdatePropietarioDTO;
use App\Mail\ClienteInvitationMail;
use App\Models\Propietario;
use App\Models\TipoDocumento;
use App\Services\Contracts\PropietarioServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PropietarioService implements PropietarioServiceInterface
{
    public function getAll(): Collection
    {
        return Propietario::latest()->get();
    }

    public function findById(string $id): Propietario
    {
        return Propietario::findOrFail($id);
    }

    public function create(CreatePropietarioDTO $dto): Propietario
    {
        $data = $dto->toArray();
        
        // Convertir codigo a tipo_doc_id
        if (isset($data['tipo_doc'])) {
            $tipoDocumento = TipoDocumento::where('codigo', $data['tipo_doc'])->firstOrFail();
            $data['tipo_doc_id'] = $tipoDocumento->id;
            unset($data['tipo_doc']);
        }

        // Generar token de invitación
        $data['invitation_token'] = Str::random(64);
        $data['invitation_sent_at'] = now();

        $propietario = Propietario::create($data);

        // Enviar correo de invitación
        $this->sendInvitationEmail($propietario);

        return $propietario;
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
     * Reenviar invitación (nuevo token, nuevo correo).
     */
    public function resendInvitation(string $id): Propietario
    {
        $propietario = $this->findById($id);

        $propietario->update([
            'invitation_token' => Str::random(64),
            'invitation_sent_at' => now(),
            'invitation_accepted_at' => null,
        ]);

        $this->sendInvitationEmail($propietario);

        return $propietario;
    }

    private function sendInvitationEmail(Propietario $propietario): void
    {
        $frontendUrl = config('app.frontend_url', config('app.url'));
        $registrationUrl = $frontendUrl . '/registrar-cliente?token=' . $propietario->invitation_token;

        Mail::to($propietario->email)->send(
            new ClienteInvitationMail($propietario, $registrationUrl)
        );
    }
}
