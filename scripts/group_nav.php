<?php
/**
 * Affecte navigationGroup (groupes de l'app Next.js d'origine) et masque de la
 * navigation les ressources "lignes/détails" gérées en ligne (relation managers),
 * afin de reproduire la navigation plate de l'application d'origine.
 */
$maps = [
    // dir => [groupe, visibleDansNav]
    'Clients' => ['Commercial', true],
    'BonCommandes' => ['Commercial', true],
    'Produits' => ['Produits', true],
    'OrdreProductions' => ['Production', true],
    'Lots' => ['Production', true],
    'ProduitPhysiques' => ['Production', true],
    'DefautsProductions' => ['Production', true],
    'EtapeProductions' => ['Production', true],
    'DocumentsSorties' => ['Documents de sortie', true],
    'Factures' => ['Facturation', true],
    'Avoirs' => ['Facturation', true],
    'Paiements' => ['Paiements', true],
    'Depots' => ['Stock', true],
    'MatierePremieres' => ['Stock', true],
    'Inventaires' => ['Stock', true],
    'BonTransferts' => ['Stock', true],
    'Emplacements' => ['Stock', true],
    'Hangars' => ['Stock', true],
    'RetourClients' => ['Retours clients', true],
    'Sequences' => ['Paramètres', true],
    // lignes / détails -> masqués (gérés en ligne)
    'BonCommandeLignes' => ['Commercial', false],
    'FactureLignes' => ['Facturation', false],
    'AvoirLignes' => ['Facturation', false],
    'DocumentSortieLignes' => ['Documents de sortie', false],
    'OrdreProductionLignes' => ['Production', false],
    'RetourClientLignes' => ['Retours clients', false],
    'PaiementImputations' => ['Paiements', false],
    'InventaireLignes' => ['Stock', false],
    'InventaireProduitPhysiques' => ['Stock', false],
    'ProduitEtapes' => ['Production', false],
    'ProduitMatierePremieres' => ['Production', false],
    'ProduitPhysiqueHistoriques' => ['Production', false],
    'MatierePremierePrixHistoriques' => ['Stock', false],
    'LotConsommationMatieres' => ['Production', false],
    'MouvementStockMatieres' => ['Stock', false],
    'MouvementSoldeClients' => ['Commercial', false],
    'StockMatierePremieres' => ['Stock', false],
    'LotProduits' => ['Production', false],
    'BonTransfertLignes' => ['Stock', false],
];

$files = [];
foreach (glob(__DIR__ . '/../app/Filament/Resources/*/*Resource.php') as $f) {
    if (str_contains($f, '/Pages/') || str_contains($f, '/RelationManagers/')) continue;
    $files[] = $f;
}

foreach ($files as $file) {
    $dir = basename(dirname($file));
    if (! isset($maps[$dir])) { echo "NON MAPPE: $dir\n"; continue; }
    [$group, $nav] = $maps[$dir];
    $content = file_get_contents($file);

    $propGroup = "    protected static string|\\UnitEnum|null \$navigationGroup = '$group';\n";
    if (strpos($content, '$navigationGroup') === false) {
        $content = preg_replace('/(extends Resource\s*\{)/', "$1\n$propGroup", $content, 1);
    }

    if (! $nav && strpos($content, '$shouldRegisterNavigation') === false) {
        $content = preg_replace('/(extends Resource\s*\{)/', "$1\n    protected static bool \$shouldRegisterNavigation = false;\n", $content, 1);
    }

    file_put_contents($file, $content);
    echo ($nav ? 'NAV ' : 'HIDE') . " $dir -> $group\n";
}
echo "Termine.\n";
