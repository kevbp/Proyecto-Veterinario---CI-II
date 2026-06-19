<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Animal_Alergia',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'animal_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'alergia_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'observaciones', type: 'string'),
        new OA\Property(property: 'severidad', type: 'string'),
        new OA\Property(property: 'estado_clinico', type: 'string'),
    ],
)]
class Animal_Alergia extends Model
{
    use HasUuids;

    protected $table = 'animal_alergia';

    protected $fillable = [
        'animal_id',
        'alergia_id',
        'observaciones',
        'severidad',
        'estado_clinico',
    ];

    protected $casts = [
        'animal_id' => 'string',
        'alergia_id' => 'string',
        'observaciones' => 'string',
        'severidad' => 'string',
        'estado_clinico' => 'string',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function alergia()
    {
        return $this->belongsTo(CatalogoAlergias::class, 'alergia_id');
    }
}
