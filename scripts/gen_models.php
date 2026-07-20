<?php
/**
 * Générateur de modèles Eloquent pour Nexus ERP.
 * Introspecte le schéma MySQL et les clés étrangères pour produire,
 * pour chaque table métier, une classe App\Models\<Model> avec :
 *  - $table, $fillable, $casts
 *  - relations belongsTo (depuis les FK) et hasMany (inverse)
 */

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Str;

$host = '127.0.0.1';
$db   = 'nexus_erp_laravel';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

// Tables métier (exclut users, spatie, laravel par défaut)
$businessTables = [
    'sequences', 'clients', 'depots', 'hangars', 'emplacements', 'etapes_production',
    'matieres_premieres', 'matieres_premieres_prix_historique', 'stock_matieres_premieres',
    'mouvements_stock_matiere', 'produits', 'produit_etapes', 'produit_matiere_premiere',
    'bons_commande', 'bon_commande_lignes', 'ordres_production', 'ordre_production_lignes',
    'lots', 'lot_produits', 'lot_consommation_matiere', 'produits_physiques',
    'produit_physique_historique', 'documents_sortie', 'document_sortie_lignes',
    'factures', 'facture_lignes', 'avoirs', 'avoir_lignes', 'paiements',
    'paiement_imputations', 'mouvements_solde_client', 'retours_clients',
    'retour_client_lignes', 'bons_transfert', 'bon_transfert_lignes', 'inventaires',
    'inventaire_lignes', 'inventaire_produits_physiques', 'defauts_production',
];

$modelNameForTable = function (string $table): string {
    static $override = [
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
    if (isset($override[$table])) return $override[$table];
    return Str::studly(Str::singular($table));
};

// Récupère les colonnes de chaque table
$columns = [];
foreach ($businessTables as $table) {
    $stmt = $pdo->query("SHOW COLUMNS FROM `$table`");
    $columns[$table] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Récupère les FK (INFORMATION_SCHEMA)
$fkStmt = $pdo->prepare("
    SELECT kcu.TABLE_NAME, kcu.COLUMN_NAME, kcu.REFERENCED_TABLE_NAME, kcu.REFERENCED_COLUMN_NAME
    FROM information_schema.KEY_COLUMN_USAGE kcu
    WHERE kcu.TABLE_SCHEMA = ? AND kcu.REFERENCED_TABLE_NAME IS NOT NULL
      AND kcu.TABLE_NAME IN (" . implode(',', array_map(fn($t) => $pdo->quote($t), $businessTables)) . ")
");
$fkStmt->execute([$db]);
$fks = $fkStmt->fetchAll(PDO::FETCH_ASSOC);

// Index FK par table
$fksByTable = [];
foreach ($fks as $fk) {
    $fksByTable[$fk['TABLE_NAME']][] = $fk;
}

// Map table -> model (pour relations)
$tableModel = [];
foreach ($businessTables as $t) {
    $tableModel[$t] = $modelNameForTable($t);
}
$tableModel['users'] = 'User';

$outDir = __DIR__ . '/../app/Models';
@mkdir($outDir, 0755, true);

$castType = function (string $type): ?string {
    $t = strtolower($type);
    if (str_starts_with($t, 'decimal') || str_starts_with($t, 'double') || str_starts_with($t, 'float')) return 'decimal:2';
    if (str_starts_with($t, 'tinyint(1)') || $t === 'tinyint') return 'boolean';
    if (str_starts_with($t, 'date') && !str_contains($t, 'datetime') && !str_contains($t, 'timestamp')) return 'date';
    if (str_starts_with($t, 'datetime') || str_starts_with($t, 'timestamp')) return 'datetime';
    return null;
};

foreach ($businessTables as $table) {
    $model = $tableModel[$table];
    $cols = $columns[$table];

    $fillable = [];
    $casts = [];
    foreach ($cols as $c) {
        $name = $c['Field'];
        if (in_array($name, ['id', 'created_at', 'updated_at'], true)) continue;
        $fillable[] = $name;
        $ct = $castType($c['Type']);
        if ($ct) $casts[$name] = $ct;
    }

    // Relations belongsTo depuis les FK
    $belongsTo = [];
    $skipCols = ['emplacement_able_id', 'emplacement_able_type'];
    foreach ($fksByTable[$table] ?? [] as $fk) {
        $col = $fk['COLUMN_NAME'];
        if (in_array($col, $skipCols, true)) continue;
        if (!str_ends_with($col, '_id')) continue;
        $refTable = $fk['REFERENCED_TABLE_NAME'];
        if (!isset($tableModel[$refTable])) continue;
        $relName = Str::camel(Str::beforeLast($col, '_id'));
        $relModel = $tableModel[$refTable];
        $belongsTo[$relName] = [$relModel, $col];
    }

    // Relations hasMany inverses (sauf vers users)
    $hasMany = [];
    foreach ($fks as $fk) {
        if ($fk['REFERENCED_TABLE_NAME'] !== $table) continue;
        $childTable = $fk['TABLE_NAME'];
        if (!isset($tableModel[$childTable])) continue;
        $relName = Str::camel(Str::plural($childTable));
        $childModel = $tableModel[$childTable];
        $hasMany[$relName] = $childModel;
    }

    // Rendu du fichier
    $fillableStr = implode(",\n        ", array_map(fn($f) => "'$f'", $fillable));
    $castsStr = '';
    if ($casts) {
        $lines = [];
        foreach ($casts as $k => $v) {
            $lines[] = "        '$k' => '$v',";
        }
        $castsStr = "\n    protected \$casts = [\n" . implode("\n", $lines) . "\n    ];\n";
    }

    $rels = '';
    foreach ($belongsTo as $relName => [$relModel, $col]) {
        $rels .= "\n    public function $relName(): \Illuminate\Database\Eloquent\Relations\BelongsTo\n    {\n        return \$this->belongsTo(\\App\\Models\\$relModel::class, '$col');\n    }\n";
    }
    foreach ($hasMany as $relName => $childModel) {
        $rels .= "\n    public function $relName(): \Illuminate\Database\Eloquent\Relations\HasMany\n    {\n        return \$this->hasMany(\\App\\Models\\$childModel::class);\n    }\n";
    }

    $php = "<?php\n\nnamespace App\\Models;\n\nuse Illuminate\\Database\\Eloquent\\Factories\\HasFactory;\nuse Illuminate\\Database\\Eloquent\\Model;\nuse Illuminate\\Database\\Eloquent\\Relations\\BelongsTo;\nuse Illuminate\\Database\\Eloquent\\Relations\\HasMany;\n\nclass $model extends Model\n{\n    use HasFactory;\n\n    protected \$table = '$table';\n\n    protected \$fillable = [\n        $fillableStr\n    ];\n$castsStr$rels}\n";

    file_put_contents("$outDir/$model.php", $php);
    echo "Modèle généré: $model (table $table)\n";
}

echo "\nTerminé. " . count($businessTables) . " modèles écrits dans app/Models.\n";
