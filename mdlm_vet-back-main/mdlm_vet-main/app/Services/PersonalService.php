<?php

namespace App\Services;

use App\Models\Personal;
use App\DTOs\Personal\CreatePersonalDTO;
use App\DTOs\Personal\UpdatePersonalDTO;
use App\Services\Contracts\PersonalServiceInterface;
use App\Mail\PersonalInvitationMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class PersonalService implements PersonalServiceInterface
{
    public function getAll()
    {
        return Personal::with(['tipoDocumento', 'user'])->get();
    }

    public function getById(string $id): ?Personal
    {
        return Personal::with(['tipoDocumento', 'user'])->findOrFail($id);
    }

    public function create(CreatePersonalDTO $dto): Personal
    {
        $data = $dto->toArray();
        $data['invitation_token'] = Str::random(64);
        $data['invitation_sent_at'] = now();

        $personal = Personal::create($data);

        Mail::to($personal->email)->send(new PersonalInvitationMail($personal));

        return $personal;
    }

    public function update(string $id, UpdatePersonalDTO $dto): Personal
    {
        $personal = $this->getById($id);
        $personal->update($dto->toArray());
        return $personal->fresh(['tipoDocumento', 'user']);
    }

    public function delete(string $id): void
    {
        $personal = $this->getById($id);
        $personal->delete();
    }

    public function resendInvitation(string $id): Personal
    {
        $personal = $this->getById($id);
        
        $personal->invitation_token = Str::random(64);
        $personal->invitation_sent_at = now();
        $personal->save();

        Mail::to($personal->email)->send(new PersonalInvitationMail($personal));

        return $personal;
    }
}
