@php
    // html5-qrcode est servi via son build UMD précompilé (public/vendor) en CHEMIN
    // RELATIF : Vite 8 / rolldown ne build pas sur Termux/Android, et @vite()/Vite::asset
    // produiraient une URL absolue sur APP_URL=http://localhost -> 404 en accès distant
    // (LAN :8443) et la lib ne se chargerait pas. Le chemin relatif garde le bon
    // hôte/port de la page courante (contexte sécurisé HTTPS préservé).
@endphp
<script src="/vendor/html5-qrcode.min.js"></script>
<script src="/js/scan-station.js"></script>

<div class="scan-wrap">
    <style>
        .scan-wrap { display: flex; flex-direction: column; gap: 1.25rem; }
        .scan-card {
            background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.75rem;
            padding: 1rem 1.25rem;
        }
        .scan-form { display: flex; flex-direction: column; gap: 0.75rem; }
        .scan-label { font-weight: 600; font-size: 0.9rem; color: #374151; }
        .scan-input {
            width: 100%; padding: 0.6rem 0.75rem; border-radius: 0.5rem;
            border: 1px solid #d1d5db; font-size: 1.1rem; letter-spacing: 0.05em;
        }
        .scan-btns { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .scan-btn {
            align-self: flex-start; background: #d97706; color: #fff; border: 0;
            border-radius: 0.5rem; padding: 0.55rem 1.25rem; font-weight: 600; cursor: pointer;
        }
        .scan-btn-secondary {
            background: #2563eb; color: #fff; border: 0; border-radius: 0.5rem;
            padding: 0.55rem 1.25rem; font-weight: 600; cursor: pointer;
        }
        .scan-row { display: grid; grid-template-columns: 180px 1fr; gap: 0.5rem 1rem; font-size: 0.95rem; }
        .scan-row dt { color: #6b7280; font-weight: 500; }
        .scan-row dd { margin: 0; font-weight: 600; color: #111827; }
        .scan-error { color: #b91c1c; font-weight: 600; }
        .scan-svg { background: #fff; padding: 0.5rem; border-radius: 0.5rem; border: 1px solid #e5e7eb; display: inline-block; }
        #reader { width: 100%; max-width: 420px; margin-top: 0.5rem; }
        #scan-msg { color: #b91c1c; font-weight: 600; margin-top: 0.5rem; }
        .scan-table-wrap { overflow-x: auto; margin-top: 0.75rem; }
        table.scan-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        table.scan-table th, table.scan-table td {
            text-align: left; padding: 0.5rem 0.75rem; border-bottom: 1px solid #e5e7eb;
        }
        table.scan-table th { background: #f3f4f6; color: #374151; text-transform: uppercase; font-size: 0.72rem; letter-spacing: 0.04em; }
        table.scan-table td.num { text-align: right; font-variant-numeric: tabular-nums; }
        .scan-badge { display: inline-block; padding: 0.1rem 0.5rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 600; }
        .scan-badge-prod { background: #fef3c7; color: #92400e; }
        .scan-badge-stock { background: #dcfce7; color: #166534; }
        .scan-badge-livre { background: #dbeafe; color: #1e40af; }
        .scan-badge-defaut { background: #fee2e2; color: #991b1b; }
        .scan-section-title { font-size: 1.05rem; font-weight: 700; margin: 0 0 0.25rem; color: #111827; }
        .scan-empty { color: #6b7280; font-style: italic; }
    </style>

    <div class="scan-card">
        <form class="scan-form" id="scan-form" method="GET" action="{{ request()->getPathInfo() }}">
            <label class="scan-label" for="code">Code de l'exemplaire physique</label>
            <input class="scan-input" type="text" id="code" name="code"
                   value="{{ $code ?? '' }}" placeholder="PP00000001" autofocus>
            <div class="scan-btns">
                <button class="scan-btn" type="submit">Chercher</button>
                <button class="scan-btn-secondary" type="button" id="btn-scanner">📷 Scanner</button>
            </div>
        </form>
        <div id="reader" style="display:none;"></div>
        <div id="scan-msg"></div>
    </div>

    @if($code && !$result)
        <div class="scan-card">
            <p class="scan-error">Aucun exemplaire physique trouvé pour le code « {{ $code }} ».</p>
        </div>
    @endif

    @if($result)
        <div class="scan-card">
            <dl class="scan-row">
                <dt>Produit</dt><dd>{{ $result->produit?->nom }} ({{ $result->produit?->reference ?? '—' }})</dd>
                <dt>Commande</dt><dd>{{ $result->lotProduit?->lot?->ordreProduction?->bonCommande?->numero_bc ?? '—' }}</dd>
                <dt>Emplacement</dt><dd>{{ $result->emplacement?->nom ?? '—' }}</dd>
                <dt>Étape actuelle</dt><dd>{{ $result->etapeActuelle?->nom ?? '—' }}</dd>
                <dt>Hangar</dt><dd>{{ $result->etapeActuelle?->hangar?->code ?? '—' }}</dd>
                <dt>Statut</dt><dd>{{ $result->statut?->getLabel() ?? $result->statut }}</dd>
                <dt>Code</dt><dd style="font-family:monospace">{{ $result->code_affiche }}</dd>
            </dl>
            <div class="scan-svg">
                {!! \App\Services\BarcodeService::renderStatic((string) $result->id) !!}
                {!! \App\Services\BarcodeService::rendreQrSvg($result->code_affiche, 110) !!}
            </div>
        </div>

        <div class="scan-card">
            <h3 class="scan-section-title">Stock par lieu</h3>
            @if($stockParLieu && $stockParLieu->isNotEmpty())
                <div class="scan-table-wrap">
                    <table class="scan-table">
                        <thead>
                            <tr>
                                <th>Lieu</th>
                                <th class="num">Total</th>
                                <th class="num">En production</th>
                                <th class="num">Disponible</th>
                                <th class="num">Livré</th>
                                <th class="num">Défaut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stockParLieu as $ligne)
                                <tr>
                                    <td>{{ $ligne['lieu'] }}</td>
                                    <td class="num">{{ $ligne['total'] }}</td>
                                    <td class="num"><span class="scan-badge scan-badge-prod">{{ $ligne['en_production'] }}</span></td>
                                    <td class="num"><span class="scan-badge scan-badge-stock">{{ $ligne['disponible'] }}</span></td>
                                    <td class="num"><span class="scan-badge scan-badge-livre">{{ $ligne['livre'] }}</span></td>
                                    <td class="num"><span class="scan-badge scan-badge-defaut">{{ $ligne['defaut'] }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="scan-empty">Aucun stock localisé pour ce produit.</p>
            @endif
        </div>
    @endif
</div>
