<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\TipoMovimientoInventario;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;     
use Illuminate\Database\Eloquent\Relations\BelongsTo;   
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MovimientoInventario',
    description: 'Registro de movimientos de inventario para medicamentos',
    required: ['medicamento_id', 'tipo_movimiento', 'cantidad_movimiento', 'stock_anterior', 'stock_actual'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'medicamento_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'tipo_movimiento', type: 'string', enum: ['entrada', 'salida', 'merma', 'ajuste']),
        new OA\Property(property: 'cantidad_movimiento', type: 'number', format: 'float'),
        new OA\Property(property: 'stock_anterior', type: 'number', format: 'float'),
        new OA\Property(property: 'stock_actual', type: 'number', format: 'float'),
        new OA\Property(property: 'motivo', type: 'string'),
        new OA\Property(property: 'referencia_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'referencia_tipo', type: 'string'),
        new OA\Property(property: 'personal_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class MovimientoInventario extends Model
{
    use HasUuids;

    protected $fillable = [
        'medicamento_id',
        'tipo_movimiento',
        'cantidad_movimiento',
        'stock_anterior',
        'stock_actual',
        'motivo',
        'referencia_id',
        'referencia_tipo',
        'personal_id',
    ];

    protected $casts = [
        'tipo_movimiento' => TipoMovimientoInventario::class,
        'cantidad_movimiento' => 'float',
        'stock_anterior' => 'float',
        'stock_actual' => 'float',
    ];

    public function medicamento(): BelongsTo
    {
        return $this->belongsTo(Medicamento::class);
    }

    public function personal(): BelongsTo
    {
        return $this->belongsTo(Personal::class);
    }

    public function referencia(): MorphTo
    {
        return $this->morphTo();
    }
}
