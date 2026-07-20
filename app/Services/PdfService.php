<?php

namespace App\Services;

use App\Models\DocumentsSortie;
use App\Models\Avoir;
use App\Models\Facture;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

/**
 * Génération des documents PDF (factures, bons de livraison) via dompdf.
 */
class PdfService
{
    public static function facture(Facture $facture): Response
    {
        $facture->load(['client', 'factureLignes.produit', 'bonCommande']);

        $reste = max(0, ($facture->montant_ttc ?? 0) - ($facture->montant_paye ?? 0));

        $pdf = Pdf::loadView('pdf.facture', [
            'f' => $facture,
            'lignes' => $facture->factureLignes,
            'reste' => $reste,
        ]);
        $pdf->setPaper('A4');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'defaultFont' => 'Helvetica']);

        return $pdf->stream('facture-' . $facture->numero_facture . '.pdf');
    }

    public static function avoir(Avoir $avoir): Response
    {
        $avoir->load(['client', 'avoirLignes.produit', 'facture']);

        $pdf = Pdf::loadView('pdf.avoir', [
            'a' => $avoir,
            'lignes' => $avoir->avoirLignes,
        ]);
        $pdf->setPaper('A4');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'defaultFont' => 'Helvetica']);

        return $pdf->stream('avoir-' . $avoir->numero_avoir . '.pdf');
    }

    public static function bonLivraison(DocumentsSortie $document): Response
    {
        $document->load(['client', 'documentSortieLignes.produitPhysique.produit', 'bonCommande']);

        $pdf = Pdf::loadView('pdf.bon-livraison', [
            'd' => $document,
            'lignes' => $document->documentSortieLignes,
        ]);
        $pdf->setPaper('A4');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'defaultFont' => 'Helvetica']);

        return $pdf->stream('bon-livraison-' . $document->numero . '.pdf');
    }
}
