<?php

namespace App\Exports;

use App\Models\MovimientoInventario;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FlujoInventarioExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function __construct(
        private readonly string $fechaInicio,
        private readonly string $fechaFin,
        private readonly ?string $medicamentoId = null,
    ){}

    public function collection()
    {
        $query = MovimientoInventario::with(['medicamento', 'personal.user', 'referencia'])
            ->whereBetween('created_at', [$this->fechaInicio . ' 00:00:00', $this->fechaFin . ' 23:59:59'])
            ->orderBy('created_at', 'asc');

        if ($this->medicamentoId) {
            $query->where('medicamento_id', $this->medicamentoId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Fecha y Hora',
            'Código Producto',
            'Medicamento / Insumo',
            'Tipo de Movimiento',
            'Motivo',
            'Origen / Referencia',
            'Stock Anterior',
            'Cantidad Movida',
            'Stock Resultante',
            'Registrado Por',
            // 'Costo Unitario',
            // 'Costo Total',
        ];
    }

    public function map($movimiento): array
    {
        // Resolución del Polimorfismo (¡Magia pura!)
        $origen = $movimiento->referencia;
        $detalleOrigen = 'Ingreso/Ajuste Manual'; // Por defecto para los NULL

        if ($origen) {
            if ($origen instanceof \App\Models\VacunaAnimal) {
                // Si la vacuna tiene animal cargado, mostramos su nombre
                $detalleOrigen = "Vacunación - Paciente: " . ($origen->animal->nombre ?? 'N/A');
            } elseif ($origen instanceof \App\Models\Desparasitacion) {
                $detalleOrigen = "Desparasitación - Paciente: " . ($origen->animal->nombre ?? 'N/A');
            } elseif ($origen instanceof \App\Models\Campania) {
                $detalleOrigen = "Descargo Masivo - Campaña: {$origen->nombre}";
            }
        }

        return [
            $movimiento->created_at->format('d/m/Y H:i'),
            $movimiento->medicamento->codigo ?? 'N/A',
            $movimiento->medicamento->nombre ?? 'N/A',
            strtoupper($movimiento->tipo_movimiento->value),
            $movimiento->motivo,
            $detalleOrigen,
            $movimiento->stock_anterior,
            // Si es salida le ponemos un signo negativo visualmente para el contador
            in_array($movimiento->tipo_movimiento->value, ['salida', 'merma', 'ajuste_negativo']) 
                ? '-' . $movimiento->cantidad_movimiento 
                : '+' . $movimiento->cantidad_movimiento,
            $movimiento->stock_actual,
            $movimiento->personal->user->name ?? 'Sistema',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
