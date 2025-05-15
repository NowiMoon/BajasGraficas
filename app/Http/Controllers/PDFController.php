<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller
{
    function downloadPDF(Request $request)
    {
        $user = Auth::user();
        if($user->user_type == 1) {
            $rol = 'Administrador';
        } elseif($user->user_type == 2) {
            $rol = 'Coordinador';
        } else {
            $rol = 'Trabajador';
        }

        $data = [
        'periodoConsulta'   => 'Periodo de consulta',
        'fecha'             => now()->format('d/m/Y'),
        'hora'              => now()->format('H:i:s'),
        'nombreGrafica'     => 'Nombre de la gráfica',
        'descripcionGrafica'=> 'Descripción de la gráfica',
        'nombreUsuario'     => $user->name ?? 'N/A',
        'rolUsuario'        => $rol,
        ];

        $pdf = Pdf::loadView('pdf.reporte', $data);

        return $pdf->download('reporte.pdf');   
    }
}