<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "TipoDocumento",
    title: "TipoDocumento",
    description: "TipoDocumento",
    required: ['codigo','nombre'],
    properties: [
        new OA\Property(property: "id",type: "string",format: "uuid",example: "123e4567-e89b-12d3-a456-426614174000"),
        new OA\Property(property: "codigo",type: "string",example: "DNI"),
        new OA\Property(property: "nombre",type: "string",example: "Documento Nacional de Identidad"),
        new OA\Property(property: "created_at",type: "string",format: "date-time"),
        new OA\Property(property: "updated_at",type: "string",format: "date-time")
    ]
)]
class TipoDocumento extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'codigo',
        'nombre'
    ];

    protected $casts = [
        'codigo' => 'string',
        'nombre' => 'string',
    ];
    //
}
