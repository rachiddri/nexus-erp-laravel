<?php

use App\Models\DocumentsSortie;
use App\Models\Facture;
use App\Models\Avoir;
use App\Services\PdfService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Documents PDF (session admin requise)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/factures/{facture}/pdf', function (Facture $facture) {
        return PdfService::facture($facture);
    })->name('factures.pdf');

    Route::get('/documents-sortie/{document}/pdf', function (DocumentsSortie $document) {
        return PdfService::bonLivraison($document);
    })->name('documents-sortie.pdf');

    Route::get('/avoirs/{avoir}/pdf', function (Avoir $avoir) {
        return PdfService::avoir($avoir);
    })->name('avoirs.pdf');
});
