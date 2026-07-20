<?php

use App\Models\BonCommande;
use App\Models\Client;
use App\Models\Produit;
use App\Models\ProduitPhysique;
use App\Services\CommandeService;
use App\Services\PrefixeDocument;
use App\Services\SequenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

/*
 * API mobile (miroir de l'existant Next.js) — authentification par token Sanctum.
 *   POST /api/v1/login  { email, password }  -> { token, user }
 *   les routes suivantes exigent  Authorization: Bearer <token>
 */

Route::post('/v1/login', function (Request $request) {
    $v = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
    ]);
    if ($v->fails()) return response()->json(['error' => $v->errors()], 422);

    if (! Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['error' => 'Identifiants invalides'], 401);
    }

    $user = Auth::user();
    $token = $user->createToken('mobile')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->getRoleNames(),
        ],
    ]);
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    Route::get('/me', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->getRoleNames(),
        ]);
    });

    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['ok' => true]);
    });

    Route::get('/clients', function (Request $request) {
        return Client::when($request->q, fn ($q) => $q->where('raison_sociale', 'like', '%' . $request->q . '%'))
            ->orderBy('raison_sociale')
            ->paginate(50);
    });

    Route::get('/produits', function (Request $request) {
        return Produit::when($request->q, fn ($q) => $q->where('nom', 'like', '%' . $request->q . '%'))
            ->orderBy('nom')
            ->paginate(50);
    });

    // Prochain numéro de document (BC, FAC, BL, OP, LOT, ...)
    Route::get('/sequence/next', function (Request $request) {
        $type = strtolower((string) $request->query('type', 'bc'));
        $map = [
            'bc' => PrefixeDocument::BC,
            'op' => PrefixeDocument::OP,
            'lot' => PrefixeDocument::LOT,
            'ds' => PrefixeDocument::BL,
            'fac' => PrefixeDocument::FAC,
            'av' => PrefixeDocument::AV,
            'paie' => PrefixeDocument::PAIE,
            'bt' => PrefixeDocument::BT,
            'inv' => PrefixeDocument::INV,
            'ret' => PrefixeDocument::RET,
            'trf' => PrefixeDocument::TRF,
            'phy' => PrefixeDocument::PHY,
        ];
        if (! isset($map[$type])) {
            return response()->json(['error' => 'type inconnu'], 422);
        }
        $p = $map[$type];
        $annee = (int) now()->format('Y');
        $n = SequenceService::prochainNumero($p, $annee);
        $numero = SequenceService::formatNumero($p->prefixe(), $annee, $n);

        return response()->json(['type' => $type, 'numero' => $numero]);
    });

    Route::get('/bons-commande', function () {
        return BonCommande::with('client')->orderByDesc('id')->paginate(50);
    });

    Route::post('/bons-commande', function (Request $request) {
        $v = Validator::make($request->all(), [
            'clientId' => 'required|exists:clients,id',
            'lignes' => 'required|array|min:1',
            'lignes.*.produitId' => 'required|exists:produits,id',
            'lignes.*.quantite' => 'required|numeric|min:0.0001',
            'lignes.*.prixUnitaire' => 'required|numeric|min:0',
        ]);
        if ($v->fails()) return response()->json(['error' => $v->errors()], 422);

        try {
            $bc = CommandeService::creerDevis([
                'clientId' => $request->clientId,
                'commercialId' => $request->user()->id,
                'notes' => $request->notes,
                'lignes' => $request->lignes,
            ]);
        } catch (\App\Services\CommandeValidationError $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json($bc, 201);
    });

    // Traçabilité : lookup d'un exemplaire physique par son code
    Route::get('/produits-physiques/lookup', function (Request $request) {
        $code = $request->query('code');
        if (! $code) return response()->json(['error' => 'code requis'], 422);

        $pp = ProduitPhysique::with(['produit', 'emplacement', 'etapeActuelle', 'lotProduit.lot.ordreProduction.bonCommande'])
            ->where('code_affiche', $code)
            ->first();

        if (! $pp) return response()->json(['error' => 'introuvable'], 404);

        return response()->json([
            'code' => $pp->code_affiche,
            'statut' => $pp->statut?->value,
            'produit' => $pp->produit?->nom,
            'reference' => $pp->produit?->reference,
            'emplacement' => $pp->emplacement?->nom,
            'etape' => $pp->etapeActuelle?->nom,
            'commande' => $pp->lotProduit?->lot?->ordreProduction?->bonCommande?->numero_bc,
        ]);
    });
});
