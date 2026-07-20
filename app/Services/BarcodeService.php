<?php

namespace App\Services;

use Picqer\Barcode\BarcodeGeneratorSVG;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * Génération d'étiquettes : QR-code (simple-qrcode) et code-barres 1D (picqer).
 */
class BarcodeService
{
    /**
     * SVG QR-code du code d'un exemplaire physique.
     */
    public static function rendreQrSvg(string $code, int $size = 120): string
    {
        return (string) QrCode::format('svg')
            ->size($size)
            ->margin(1)
            ->errorCorrection('M')
            ->generate($code);
    }

    /**
     * SVG code-barres (Code-128) — utilisé pour l'impression des étiquettes.
     */
    public static function renderStatic(string $value): string
    {
        $generator = new BarcodeGeneratorSVG();
        return $generator->getBarcode($value, $generator::TYPE_CODE_128);
    }
}
