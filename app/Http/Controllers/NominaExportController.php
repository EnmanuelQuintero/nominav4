<?php

namespace App\Http\Controllers;

use App\Models\Nomina;
use Barryvdh\DomPDF\Facade\Pdf;

class NominaExportController extends Controller
{
    public function pdf($id)
    {
        $nomina = Nomina::with('detalles')->findOrFail($id);

        $detallesAgrupados = $nomina->detalles->groupBy('area');
        $pdf = Pdf::loadView('nominas.pdf', compact('nomina','detallesAgrupados'))->setPaper('A4', 'landscape');

        return $pdf->download('nomina-'.$nomina->codigo.'.pdf');
    }
}