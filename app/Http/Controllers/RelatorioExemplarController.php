<?php

namespace App\Http\Controllers;

use App\Models\Titulo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RelatorioExemplarController extends Controller
{
    /**
     * Generate the PDF report with every registered copy, grouped by title.
     */
    public function __invoke(Request $request): Response
    {
        $titulos = Titulo::query()
            ->whereHas('exemplares')
            ->with([
                'editora',
                'exemplares' => fn (HasMany $query) => $query->orderBy('exemplar_codigo'),
                'exemplares.armazenagem',
                'exemplares.tamanho',
            ])
            ->orderBy('titulo_descricao')
            ->get();

        $geradoEm = now();

        $pdf = Pdf::loadView('relatorios.pdf.exemplares', [
            'titulos' => $titulos,
            'geradoEm' => $geradoEm,
            'geradoPor' => $request->user()->name,
        ])
            ->setPaper('a4')
            ->setOption('isFontSubsettingEnabled', true);

        $pdf->render();

        $dompdf = $pdf->getDomPDF();
        $dompdf->getCanvas()->page_text(
            480,
            812,
            'Página {PAGE_NUM} de {PAGE_COUNT}',
            $dompdf->getFontMetrics()->getFont('DejaVu Sans'),
            8,
            [0.39, 0.45, 0.55],
        );

        return $pdf->stream('exemplares-'.$geradoEm->format('Y-m-d').'.pdf');
    }
}
