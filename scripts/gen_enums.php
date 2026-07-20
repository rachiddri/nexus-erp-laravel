<?php
/**
 * Générateur d'enums PHP pour Nexus ERP (port des enums TypeScript).
 * Chaque enum implémente Filament\Support\Contracts\HasLabel.
 */

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Str;
use Illuminate\Support\Collection;

$spec = [
    'StatutBonCommande' => [
        'devis' => 'Devis',
        'brouillon' => 'Brouillon',
        'confirmee' => 'Confirmée',
        'en_production' => 'En production',
        'partiellement_prete' => 'Partiellement prête',
        'prete' => 'Prête',
        'partiellement_livree' => 'Partiellement livrée',
        'livree' => 'Livrée',
        'annulee' => 'Annulée',
    ],
    'StatutOrdreProduction' => [
        'brouillon' => 'Brouillon',
        'confirme' => 'Confirmé',
        'en_cours' => 'En cours',
        'termine' => 'Terminé',
        'annule' => 'Annulé',
    ],
    'StatutLot' => [
        'en_attente' => 'En attente',
        'en_production' => 'En production',
        'partiel' => 'Partiel',
        'termine' => 'Terminé',
        'termine_avec_rebut' => 'Terminé avec rebut',
        'rebute' => 'Rebuté',
        'annule' => 'Annulé',
    ],
    'StatutLotProduit' => [
        'en_attente' => 'En attente',
        'en_production' => 'En production',
        'partiel' => 'Partiel',
        'termine' => 'Terminé',
        'termine_avec_rebut' => 'Terminé avec rebut',
        'rebute' => 'Rebuté',
    ],
    'StatutProduitPhysique' => [
        'en_production' => 'En production',
        'disponible' => 'Disponible',
        'reserve' => 'Réservé',
        'vendu' => 'Vendu',
        'livre' => 'Livré',
        'defectueux' => 'Défectueux',
        'rebut' => 'Rebut',
        'retourne' => 'Retourné',
        'annule' => 'Annulé',
    ],
    'StatutFacture' => [
        'brouillon' => 'Brouillon',
        'emise' => 'Émise',
        'partiellement_payee' => 'Partiellement payée',
        'payee' => 'Payée',
        'annulee_par_avoir' => 'Annulée par avoir',
    ],
    'StatutPaiement' => [
        'en_attente' => 'En attente',
        'encaisse' => 'Encaissé',
        'rejete' => 'Rejeté',
        'annule' => 'Annulé',
    ],
    'TypeMouvementStockMatiere' => [
        'entree_achat' => 'Entrée achat',
        'sortie_production' => 'Sortie production',
        'ajustement_inventaire' => 'Ajustement inventaire',
        'transfert' => 'Transfert',
    ],
    'TypeMouvementPhysique' => [
        'passage_etape' => 'Passage étape',
        'transfert_depot' => 'Transfert dépôt',
        'reservation' => 'Réservation',
        'livraison' => 'Livraison',
        'retour' => 'Retour',
        'defaut' => 'Défaut',
        'annulation' => 'Annulation',
    ],
    'MethodeScan' => [
        'camera_mobile' => 'Caméra mobile',
        'saisie_manuelle_mobile' => 'Saisie manuelle mobile',
        'camera_web' => 'Caméra web',
        'saisie_manuelle_web' => 'Saisie manuelle web',
        'api' => 'API',
    ],
    'GraviteDefaut' => [
        'mineur' => 'Mineur',
        'majeur' => 'Majeur',
        'critique' => 'Critique',
    ],
    'DecisionDefaut' => [
        'en_attente' => 'En attente',
        'rebut' => 'Rebut',
        'retouche' => 'Retouche',
        'declasse' => 'Déclasse',
    ],
    'DecisionRetour' => [
        'remise_en_stock' => 'Remise en stock',
        'rebut' => 'Rebut',
        'retouche' => 'Retouche',
    ],
    'MotifAvoir' => [
        'annulation' => 'Annulation',
        'retour_client' => 'Retour client',
        'erreur_facturation' => 'Erreur de facturation',
        'geste_commercial' => 'Geste commercial',
    ],
    'TypeDocumentSortie' => [
        'livraison' => 'Livraison',
        'enlevement' => 'Enlèvement',
    ],
    'StatutDocumentSortie' => [
        'prepare' => 'Préparé',
        'effectue' => 'Effectué',
        'annule' => 'Annulé',
    ],
    'StatutBonTransfert' => [
        'prepare' => 'Préparé',
        'expedie' => 'Expédié',
        'recu' => 'Reçu',
        'annule' => 'Annulé',
    ],
    'TypeInventaire' => [
        'matiere' => 'Matière',
        'produit_fini' => 'Produit fini',
    ],
    'StatutInventaire' => [
        'en_cours' => 'En cours',
        'valide' => 'Validé',
        'annule' => 'Annulé',
    ],
    'ResultatInventairePhysique' => [
        'present' => 'Présent',
        'introuvable' => 'Introuvable',
        'trouve_non_attendu' => 'Trouvé non attendu',
    ],
    'OrigineOrdreProduction' => [
        'commande' => 'Commande',
        'stock' => 'Stock',
    ],
    'TypeDepot' => [
        'matiere' => 'Matière',
        'produit_fini' => 'Produit fini',
        'mixte' => 'Mixte',
    ],
    'ModePaiement' => [
        'espece' => 'Espèce',
        'cheque' => 'Chèque',
        'virement' => 'Virement',
        'traite' => 'Traite',
    ],
    'StatutClient' => [
        'actif' => 'Actif',
        'bloque' => 'Bloqué',
    ],
    'StatutDefaut' => [
        'ouvert' => 'Ouvert',
        'traite' => 'Traité',
    ],
    'StatutRetour' => [
        'recu' => 'Reçu',
        'traite' => 'Traité',
    ],
    'EtatProduit' => [
        'defectueux' => 'Défectueux',
        'conforme' => 'Conforme',
        'rebut' => 'Rebut',
    ],
];

$outDir = __DIR__ . '/../app/Enums';
@mkdir($outDir, 0755, true);

foreach ($spec as $enumName => $cases) {
    $caseDefs = [];
    $matchArms = [];
    foreach ($cases as $value => $label) {
        $caseName = Str::studly($value);
        $caseDefs[] = "    case $caseName = '$value';";
        $matchArms[] = "            self::$caseName => '$label',";
    }
    $caseStr = implode("\n", $caseDefs);
    $matchStr = implode("\n", $matchArms);

    $php = "<?php\n\nnamespace App\\Enums;\n\nuse Filament\\Support\\Contracts\\HasLabel;\nuse Illuminate\\Support\\Collection;\n\nenum $enumName: string implements HasLabel\n{\n$caseStr\n\n    public function getLabel(): string\n    {\n        return match (\$this) {\n$matchStr\n        };\n    }\n\n    /** @return array<string,string> */\n    public static function options(): array\n    {\n        return Collection::make(self::cases())\n            ->mapWithKeys(fn (self \$c) => [\$c->value => \$c->getLabel()])\n            ->all();\n    }\n}\n";

    file_put_contents("$outDir/$enumName.php", $php);
    echo "Enum généré: $enumName (" . count($cases) . " cas)\n";
}

echo "\nTerminé. " . count($spec) . " enums écrits dans app/Enums.\n";
