<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Avoir {{ $a->numero_avoir }}</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #1f2937; font-size: 12px; }
        .header { display: flex; justify-content: space-between; margin-bottom: 24px; }
        .company { font-size: 16px; font-weight: bold; color: #b45309; }
        .title { font-size: 22px; font-weight: bold; color: #111827; }
        .meta { width: 100%; border-collapse: collapse; margin: 12px 0; }
        .meta td { padding: 3px 6px; vertical-align: top; }
        .meta .label { color: #6b7280; width: 35%; }
        table.lignes { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.lignes th { background: #f3f4f6; text-align: left; padding: 6px; font-size: 11px; }
        table.lignes td { padding: 6px; border-bottom: 1px solid #e5e7eb; }
        table.lignes td.num { text-align: right; }
        .totaux { width: 40%; margin-left: auto; margin-top: 12px; border-collapse: collapse; }
        .totaux td { padding: 4px 6px; }
        .totaux td.num { text-align: right; }
        .totaux tr.total td { font-weight: bold; font-size: 13px; border-top: 2px solid #111827; }
        .footer { margin-top: 30px; color: #9ca3af; font-size: 10px; text-align: center; }
        .badge { display: inline-block; background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 9999px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="company">NEXUS ERP</div>
            <div>SIÈGE — Alger, Algérie</div>
            <div>Tél : +213 ... — contact@nexus-erp.dz</div>
        </div>
        <div style="text-align:right">
            <div class="title">AVOIR</div>
            <div>N° <strong>{{ $a->numero_avoir }}</strong></div>
            <div class="badge">{{ $a->statut }}</div>
        </div>
    </div>

    <table class="meta">
        <tr>
            <td class="label">Client</td>
            <td><strong>{{ $a->client->raison_sociale ?? '—' }}</strong><br>
                {{ $a->client->adresse ?? '' }}<br>
                {{ $a->client->telephone ?? '' }}
            </td>
            <td class="label">Date de l'avoir</td>
            <td>{{ $a->date_avoir?->format('d/m/Y') ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Facture d'origine</td>
            <td>{{ $a->facture->numero_facture ?? '—' }}</td>
            <td class="label">Motif</td>
            <td>{{ $a->motif ?? '—' }}</td>
        </tr>
    </table>

    <table class="lignes">
        <thead>
            <tr>
                <th>Désignation</th>
                <th style="width:80px">Qté</th>
                <th style="width:100px">P.U. HT</th>
                <th style="width:100px">Montant HT</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lignes as $l)
                <tr>
                    <td>{{ $l->designation ?? ($l->produit->nom ?? '—') }}</td>
                    <td class="num">{{ number_format($l->quantite, 2, ',', ' ') }}</td>
                    <td class="num">{{ number_format($l->prix_unitaire, 2, ',', ' ') }}</td>
                    <td class="num">{{ number_format($l->montant_total, 2, ',', ' ') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align:center;color:#9ca3af">Aucune ligne</td></tr>
            @endforelse
        </tbody>
    </table>

    <table class="totaux">
        <tr><td>Total HT (somme des lignes)</td><td class="num">{{ number_format($a->montant_ht, 2, ',', ' ') }}</td></tr>
        <tr><td>TVA ({{ number_format((float)$a->taux_tva, 2, ',', ' ') }} %)</td><td class="num">{{ number_format($a->montant_tva, 2, ',', ' ') }}</td></tr>
        <tr class="total"><td>Total TTC</td><td class="num">{{ number_format($a->montant_ttc, 2, ',', ' ') }}</td></tr>
    </table>

    <p style="margin-top:10px"><strong>Montant TTC en lettres :</strong> {{ $a->montant_ttc_lettres ?: \App\Helpers\NumberToWords::enLettres($a->montant_ttc) }} Dinars</p>

    @if($a->motif)
        <p style="margin-top:16px"><strong>Motif :</strong> {{ $a->motif }}</p>
    @endif

    <div class="footer">NEXUS ERP — Document généré automatiquement.</div>
</body>
</html>
