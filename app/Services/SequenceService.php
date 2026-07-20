<?php

namespace App\Services;

use App\Models\Sequence;
use Illuminate\Support\Facades\DB;

/**
 * SequenceService — Numérotation centralisée anti-collision (§6.1).
 * Équivalent Laravel du SequenceService TypeScript (lockForUpdate).
 */
class SequenceService
{
    /**
     * Récupère le prochain numéro de séquence pour un type de document.
     * DOIT être appelé dans une transaction (SELECT ... FOR UPDATE).
     */
    public static function prochainNumero(PrefixeDocument $prefixeDoc, int $annee = null): int
    {
        $annee ??= (int) now()->format('Y');
        $prefixe = $prefixeDoc->prefixe();

        return DB::transaction(function () use ($prefixe, $annee) {
            $seq = Sequence::where('prefixe', $prefixe)
                ->where('annee', $annee)
                ->lockForUpdate()
                ->first();

            if (! $seq) {
                $seq = Sequence::create(['prefixe' => $prefixe, 'annee' => $annee, 'dernier_numero' => 0]);
            }

            $nouvelleValeur = $seq->dernier_numero + 1;
            $seq->update(['dernier_numero' => $nouvelleValeur]);

            return $nouvelleValeur;
        });
    }

    /**
     * Formate un numéro de document : OP-2026-000045 (BL/BE : BL-2026-000045).
     */
    public static function formatNumero(string $prefixe, int $annee, int $n, int $pad = 6): string
    {
        return match ($prefixe) {
            'ds' => strtoupper($prefixe === 'ds' && $n > 0 ? 'BL' : 'BL') . "-{$annee}-" . str_pad((string) $n, $pad, '0', STR_PAD_LEFT),
            default => strtoupper($prefixe) . "-{$annee}-" . str_pad((string) $n, $pad, '0', STR_PAD_LEFT),
        };
    }

    /**
     * Génère un code produit physique basé sur le lot : PHY-2026-45-0001.
     */
    public static function genererCodePhysique(int $annee, string $numeroLot, int $seqDansLot): string
    {
        $lotNum = str_replace('LOT-', '', $numeroLot);

        return "PHY-{$annee}-{$lotNum}-" . str_pad((string) $seqDansLot, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Prochain numéro local à un lot (pas de concurrence).
     */
    public static function prochainDansLot(int $quantiteProduite, int $totalProduit): int
    {
        return $totalProduit + 1;
    }
}
