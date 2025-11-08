<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PDFController extends Controller
{
    public function downloadPDF(Request $request)
    {

        try {
            $user = Auth::user();
            $rol = match ($user->user_type) {
                1 => 'Administrador',
                2 => 'Coordinador',
                default => 'Trabajador',
            };

            $datosGrafica = $request->input('Datos', []);
            $totalGrafica = $request->input('Total', 0);
            $nombreGrafica = $request->input('nombreGrafica', 'Gráfica sin título');
            $subtitulo = $request->input('subtituloGrafica', ''); // Nuevo campo
            $imagenGrafica = $request->input('imagenGrafica');
            $fecha = $request->input('fecha', now()->format('d/m/Y'));
            $hora = $request->input('hora', now()->format('H:i:s'));

            $data = [
                'fecha'              => $fecha,
                'hora'               => $hora,
                'nombreGrafica'      => $nombreGrafica,
                'subtitloGrafica'    => $subtitulo,
                'imagenGrafica'      => $imagenGrafica,
                'datosGrafica'       => $datosGrafica,
                'totalGrafica'       => $totalGrafica,
            ];

            Log::info($subtitulo);

            // MUY IMPORTANTE: validar que sea base64 con prefijo válido
            if (str_starts_with($data['imagenGrafica'], 'data:image')) {
                $data['imagenGrafica'] = $data['imagenGrafica'];
            } else {
                throw new \Exception("La imagen no es válida o no tiene formato base64 con prefijo.");
            }

            $pdf = Pdf::loadView('pdf.reporte', $data);
            return $pdf->download('reporte.pdf');
        } catch (\Exception $e) {
            Log::error('Error al generar PDF: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el PDF', 'detalle' => $e->getMessage()], 500);
        }
    }
}