<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Bon de livraison {{ $d->numero }}</title>
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
        .footer { margin-top: 30px; color: #9ca3af; font-size: 10px; text-align: center; }
        .badge { display: inline-block; background: #dbeafe; color: #1e40af; padding: 2px 8px; border-radius: 9999px; }
        .sign { margin-top: 40px; display:flex; justify-content: space-between; }
        .sign div { width: 45%; border-top: 1px solid #111827; padding-top: 4px; text-align:center; color:#6b7280; }
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
            <div class="title">BON DE LIVRAISON</div>
            <div>N° <strong>{{ $d->numero }}</strong></div>
            <div class="badge">{{ $d->type }} / {{ $d->statut }}</div>
        </div>
    </div>

    <table class="meta">
        <tr>
            <td class="label">Client</td>
            <td><strong>{{ $d->client->raison_sociale ?? '—' }}</strong></td>
            <td class="label">Date de sortie</td>
            <td>{{ $d->date_sortie?->format('d/m/Y') ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Bon de commande</td>
            <td>{{ $d->bonCommande->numero_bc ?? '—' }}</td>
            <td class="label">Adresse livraison</td>
            <td>{{ $d->adresse_livraison ?? '—' }}</td>
        </tr>
    </table>

    <table class="lignes">
        <thead>
            <tr>
                <th>Code exemplaire</th>
                <th>Produit</th>
                <th>N° lot</th>
                <th>Réf. produit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lignes as $l)
                <tr>
                    <td>{{ $l->produitPhysique->code_affiche ?? '—' }}</td>
                    <td>{{ $l->produitPhysique->produit->nom ?? '—' }}</td>
                    <td>{{ $l->numero_lot ?? '—' }}</td>
                    <td>{{ $l->produitPhysique->produit->reference ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align:center;color:#9ca3af">Aucune ligne</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="sign">
        <div>Le magasinier</div>
        <div>Le client</div>
    </div>

    <div class="footer">NEXUS ERP — Document généré automatiquement.</div>
</body>
</html>
