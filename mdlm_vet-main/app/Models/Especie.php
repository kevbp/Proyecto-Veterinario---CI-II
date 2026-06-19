<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Especie",
    title: "Especie",
    description: "Especie",
    required: ['codigo','nombre'],
    properties: [
        new OA\Property(property: "id",type: "string",format: "uuid",example: "123e4567-e89b-12d3-a456-426614174000"),
        new OA\Property(property: "codigo",type: "string",example: "CAN"),
        new OA\Property(property: "nombre",type: "string",example: "Canino"),
        new OA\Property(property: "created_at",type: "string",format: "date-time"),
        new OA\Property(property: "updated_at",type: "string",format: "date-time")
    ]
)]
class Especie extends Model
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

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function animales()
    {
        return $this->hasMany(Animal::class);
    }
}
