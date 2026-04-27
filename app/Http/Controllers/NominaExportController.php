<?php

namespace App\Http\Controllers;

use App\Models\Nomina;
use Barryvdh\DomPDF\Facade\Pdf;

use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\NominaDetalle;
class NominaExportController extends Controller
{
    public function pdf($id)
    {
        $nomina = Nomina::with('detalles')->findOrFail($id);

        $detallesAgrupados = $nomina->detalles->groupBy('area');
        $pdf = Pdf::loadView('nominas.pdf', compact('nomina','detallesAgrupados'))->setPaper('A4', 'landscape');

        return $pdf->download('nomina-'.$nomina->codigo.'.pdf');
    }


    public function csv($id)
    {
        $nomina = Nomina::with('detalles.empleado')->findOrFail($id);

        $response = new StreamedResponse(function () use ($nomina) {

            $handle = fopen('php://output', 'w');

            // 🔹 Encabezados
            fputcsv($handle, ['Cuenta Bancaria', 'Monto'], ';');

            $total = 0;

            foreach ($nomina->detalles as $detalle) {

                $cuenta = $detalle->empleado->cuenta_bancaria ?? '';

                $monto = number_format($detalle->neto_pagar, 2, '.', '');

                $total += $detalle->neto_pagar;

                fputcsv($handle, [$cuenta, $monto], ';');
            }

            // 🔥 FILA TOTAL
            fputcsv($handle, [], ';');
            fputcsv($handle, ['TOTAL', number_format($total, 2, '.', '')], ';');

            fclose($handle);
        });

        $filename = 'nomina_'.$nomina->id.'.csv';

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', "attachment; filename=$filename");

        return $response;
    }

    public function comprobante($detalleId)
    {
        $detalle = NominaDetalle::with('nomina')->findOrFail($detalleId);

        $pdf = Pdf::loadView('nominas.comprobante', compact('detalle'))
            ->setPaper('A5', 'portrait'); // tamaño compacto tipo recibo

        return $pdf->download('comprobante_'.$detalle->numero_empleado.'.pdf');
    }

}