<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

#[Fillable(['id', 'sso_id', 'type', 'name', 'email', 'phone'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasUuids;

    protected string $guard_name = 'api';
    // public bool $incrementing = false;
    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime'
        ];
    }


    // ─── Relationships ────────────────────────────────────────

    public function propietario(): HasOne
    {
        return $this->hasOne(Propietario::class);
    }

    public function personal(): HasOne
    {
        return $this->hasOne(Personal::class);
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function isAdmin(): bool { return $this->hasRole('admin'); }

    public function isGestor(): bool { return $this->hasRole('gestor'); }

    public function isCliente(): bool { return $this->hasRole('propietario'); }

    public function isVeterinario(): bool { return $this->hasRole('veterinario'); }

    public function isRecepcionista(): bool { return $this->hasRole('recepcionista'); }
}
