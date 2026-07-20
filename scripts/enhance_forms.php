<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// 1. FK map via INFORMATION_SCHEMA (déterministe)
$override = [
    'etapes_production' => 'EtapeProduction',
    'matieres_premieres' => 'MatierePremiere',
    'matieres_premieres_prix_historique' => 'MatierePremierePrixHistorique',
    'stock_matieres_premieres' => 'StockMatierePremiere',
    'mouvements_stock_matiere' => 'MouvementStockMatiere',
    'produits' => 'Produit',
    'produits_physiques' => 'ProduitPhysique',
    'inventaire_produits_physiques' => 'InventaireProduitPhysique',
    'bons_commande' => 'BonCommande',
    'ordres_production' => 'OrdreProduction',
    'retours_clients' => 'RetourClient',
    'bons_transfert' => 'BonTransfert',
    'mouvements_solde_client' => 'MouvementSoldeClient',
];
$modelNameForTable = function (string $t) use ($override): string {
    return $override[$t] ?? \Illuminate\Support\Str::studly(\Illuminate\Support\Str::singular($t));
};

function displayCol(string $model): string {
    return match ($model) {
        'Client' => 'raison_sociale',
        'Produit' => 'nom',
        'Depot' => 'nom',
        'Hangar' => 'nom',
        'Emplacement' => 'code_emplacement',
        'EtapeProduction' => 'nom',
        'MatierePremiere' => 'nom',
        'User' => 'name',
        'BonCommande' => 'numero_bc',
        'OrdreProduction' => 'numero_op',
        'Lot' => 'numero_lot',
        'DocumentSortie' => 'numero',
        'Facture' => 'numero_facture',
        'Avoir' => 'numero_avoir',
        'Paiement' => 'numero',
        'BonTransfert' => 'numero',
        'Inventaire' => 'numero',
        'ProduitPhysique' => 'code_affiche',
        default => 'id',
    };
}

$pdo = new PDO('mysql:host=127.0.0.1;dbname=nexus_erp_laravel;charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$businessTables = ['sequences','clients','depots','hangars','emplacements','etapes_production','matieres_premieres','matieres_premieres_prix_historique','stock_matieres_premieres','mouvements_stock_matiere','produits','produit_etapes','produit_matiere_premiere','bons_commande','bon_commande_lignes','ordres_production','ordre_production_lignes','lots','lot_produits','lot_consommation_matiere','produits_physiques','produit_physique_historique','documents_sortie','document_sortie_lignes','factures','facture_lignes','avoirs','avoir_lignes','paiements','paiement_imputations','mouvements_solde_client','retours_clients','retour_client_lignes','bons_transfert','bon_transfert_lignes','inventaires','inventaire_lignes','inventaire_produits_physiques','defauts_production'];
$in = implode(',', array_map(fn($t) => $pdo->quote($t), $businessTables));
$fks = $pdo->query("SELECT kcu.COLUMN_NAME, kcu.REFERENCED_TABLE_NAME FROM information_schema.KEY_COLUMN_USAGE kcu WHERE kcu.TABLE_SCHEMA='nexus_erp_laravel' AND kcu.REFERENCED_TABLE_NAME IS NOT NULL AND kcu.TABLE_NAME IN ($in)")->fetchAll(PDO::FETCH_ASSOC);

$tableModel = [];
foreach ($businessTables as $t) { $tableModel[$t] = $modelNameForTable($t); }
$tableModel['users'] = 'User';

$relDisplay = [];   // relationName => displayColumn
foreach ($fks as $fk) {
    $col = $fk['COLUMN_NAME'];
    if (! str_ends_with($col, '_id')) continue;
    if (in_array($col, ['emplacement_able_id'], true)) continue;
    $refTable = $fk['REFERENCED_TABLE_NAME'];
    if (! isset($tableModel[$refTable])) continue;
    $relName = \Illuminate\Support\Str::camel(\Illuminate\Support\Str::beforeLast($col, '_id'));
    $relDisplay[$relName] = displayCol($tableModel[$refTable]);
}
// Relations utilisateur (creePar, validePar, ...) -> User (display 'name')
foreach (['creePar','validePar','encaissePar','signalePar','resoluPar','traitePar','approuvePrixPlancherPar'] as $ur) {
    $relDisplay[$ur] = 'name';
}

// 3. Enum map : table => [colonne => EnumClass]
$enumMap = [
    'bons_commande' => ['statut' => 'StatutBonCommande'],
    'ordres_production' => ['statut' => 'StatutOrdreProduction', 'origine' => 'OrigineOrdreProduction'],
    'lots' => ['statut' => 'StatutLot'],
    'lot_produits' => ['statut' => 'StatutLotProduit'],
    'produits_physiques' => ['statut' => 'StatutProduitPhysique'],
    'factures' => ['statut' => 'StatutFacture', 'mode_reglement' => 'ModePaiement'],
    'paiements' => ['statut' => 'StatutPaiement', 'mode_paiement' => 'ModePaiement'],
    'documents_sortie' => ['statut' => 'StatutDocumentSortie', 'type' => 'TypeDocumentSortie'],
    'bons_transfert' => ['statut' => 'StatutBonTransfert'],
    'inventaires' => ['statut' => 'StatutInventaire', 'type' => 'TypeInventaire'],
    'defauts_production' => ['statut' => 'StatutDefaut', 'gravite' => 'GraviteDefaut', 'decision' => 'DecisionDefaut'],
    'retours_clients' => ['statut' => 'StatutRetour', 'decision' => 'DecisionRetour'],
    'retour_client_lignes' => ['etat_produit' => 'EtatProduit'],
    'depots' => ['type' => 'TypeDepot'],
    'mouvements_stock_matiere' => ['type_mouvement' => 'TypeMouvementStockMatiere'],
    'produit_physique_historique' => ['type_mouvement' => 'TypeMouvementPhysique'],
    'avoirs' => ['motif' => 'MotifAvoir'],
];

$formDirs = glob(__DIR__ . '/../app/Filament/Resources/*', GLOB_ONLYDIR);
foreach ($formDirs as $dir) {
    $formFile = $dir . '/Schemas/' . Str::singular(basename($dir)) . 'Form.php';
    if (! file_exists($formFile)) continue;

    $modelName = basename($dir); // ex: BonCommandes -> modele BonCommande
    $modelClass = 'App\\Models\\' . Str::singular($modelName);
    if (! class_exists($modelClass)) continue;
    $table = (new $modelClass())->getTable();
    $enumsForTable = $enumMap[$table] ?? [];

    $content = file_get_contents($formFile);
    $usedEnums = [];

    // 3a. Relationship display : ->relationship('rel', 'id') -> display correct
    $content = preg_replace_callback(
        "/->relationship\(\s*'([^']+)'\s*,\s*'id'\s*\)/",
        function ($m) use ($relDisplay) {
            $rel = $m[1];
            if (! isset($relDisplay[$rel])) return $m[0];
            $disp = $relDisplay[$rel];
            return "->relationship('$rel', '$disp')";
        },
        $content
    );

    // 3b. Enum fields : TextInput::make('statut') -> Select + options
    foreach ($enumsForTable as $col => $enumClass) {
        $pattern = "/TextInput::make\(\s*'" . preg_quote($col, '/') . "'\s*\)/";
        if (preg_match($pattern, $content)) {
            $replacement = "Select::make('$col')->options(\\App\\Enums\\$enumClass::options())";
            $content = preg_replace($pattern, $replacement, $content, 1);
            $usedEnums[] = $enumClass;
        }
    }

    // 3c. Ajout des use App\Enums\...
    if ($usedEnums) {
        $uses = '';
        foreach (array_unique($usedEnums) as $e) {
            $uses .= "use App\\Enums\\$e;\n";
        }
        // insérer après le dernier "use Filament..." ou avant "class"
        if (preg_match("/(use Filament.*;)\n/m", $content, $mm, PREG_OFFSET_CAPTURE)) {
            $pos = $mm[0][1] + strlen($mm[0][0]);
            $content = substr($content, 0, $pos) . $uses . substr($content, $pos);
        } else {
            $content = preg_replace("/(namespace .*;\n\n)/", "$1$uses", $content, 1);
        }
    }

    file_put_contents($formFile, $content);
    echo "Form amélioré: " . basename($dir) . " (enums: " . implode(',', $usedEnums) . ")\n";
}

// 4. Relation managers : display FK correct (sans réflexion modèle)
foreach (glob(__DIR__ . '/../app/Filament/Resources/*/RelationManagers/*.php') as $rmFile) {
    $content = file_get_contents($rmFile);
    $content = preg_replace_callback(
        "/->relationship\(\s*'([^']+)'\s*,\s*'id'\s*\)/",
        function ($m) use ($relDisplay) {
            $rel = $m[1];
            if (! isset($relDisplay[$rel])) return $m[0];
            return "->relationship('$rel', '" . $relDisplay[$rel] . "')";
        },
        $content
    );
    file_put_contents($rmFile, $content);
    echo "RM FK display: " . basename($rmFile) . "\n";
}

echo "\nTerminé.\n";
