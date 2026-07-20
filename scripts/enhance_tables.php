<?php
/**
 * Enhance Tables — rend les colonnes de liste lisibles :
 *  - 'client.id' -> 'client.raison_sociale' (label libellé)
 *  - colonnes statut/type -> badges
 */

error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);
require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Str;

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
    return $override[$t] ?? Str::studly(Str::singular($t));
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
$relDisplay = [];
$labelMap = [
    'client' => 'Client', 'produit' => 'Produit', 'depot' => 'Dépôt', 'depotMatiere' => 'Dépôt matière',
    'bonCommande' => 'Bon de commande', 'lot' => 'Lot', 'ordreProduction' => 'Ordre de production',
    'etapeActuelle' => 'Étape', 'emplacement' => 'Emplacement', 'documentSortie' => 'Document',
    'facture' => 'Facture', 'paiement' => 'Paiement', 'avoir' => 'Avoir', 'inventaire' => 'Inventaire',
    'bonTransfert' => 'Bon de transfert', 'retourClient' => 'Retour', 'matierePremiere' => 'Matière',
    'lotProduit' => 'Lot produit', 'role' => 'Rôle',
];
foreach ($fks as $fk) {
    $col = $fk['COLUMN_NAME'];
    if (! str_ends_with($col, '_id')) continue;
    if (in_array($col, ['emplacement_able_id'], true)) continue;
    $refTable = $fk['REFERENCED_TABLE_NAME'];
    if (! isset($tableModel[$refTable])) continue;
    $relName = Str::camel(Str::beforeLast($col, '_id'));
    $relDisplay[$relName] = displayCol($tableModel[$refTable]);
}

$badgeCols = ['statut', 'type', 'etat_produit', 'mode_paiement', 'mode_reglement', 'gravite', 'decision', 'origine'];

$tableDirs = glob(__DIR__ . '/../app/Filament/Resources/*/Tables/*Table.php');
$tableDirs = array_merge($tableDirs, glob(__DIR__ . '/../app/Filament/Resources/*/RelationManagers/*.php'));
foreach ($tableDirs as $file) {
    $content = file_get_contents($file);

    // 1. FK dot columns : 'client.id' -> 'client.raison_sociale' + label
    $content = preg_replace_callback(
        "/TextColumn::make\(\s*'([a-zA-Z_]+)\\.id'\s*\)/",
        function ($m) use ($relDisplay, $labelMap) {
            $rel = $m[1];
            if (! isset($relDisplay[$rel])) return $m[0];
            $disp = $relDisplay[$rel];
            $label = $labelMap[$rel] ?? Str::headline($rel);
            return "TextColumn::make('$rel.$disp')->label('$label')";
        },
        $content
    );

    // 2. Badges pour statut/type (idempotent)
    foreach ($badgeCols as $col) {
        $pattern = "/TextColumn::make\(\s*'" . preg_quote($col, '/') . "'\s*\)(?!\s*->badge\(\))/s";
        if (preg_match($pattern, $content)) {
            $content = preg_replace($pattern, "TextColumn::make('$col')->badge()", $content, 1);
        }
    }

    file_put_contents($file, $content);
    echo "Table améliorée: " . basename($file) . "\n";
}
echo "\nTerminé.\n";
