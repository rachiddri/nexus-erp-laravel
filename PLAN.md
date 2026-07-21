# Plan Nexus ERP — Laravel 13 + Filament 4 + Shield

> Copie 100% fidèle du système original Next.js+Prisma, reconstruit avec **Laravel 13.20**, **Filament 4.x**, **Shield 4.x** (spatie/laravel-permission), **MariaDB/MySQL** et **Sanctum** (API).

---

## 1. Stack & Versions

| Composant | Version | Usage |
|-----------|---------|-------|
| **Laravel** | 13.20 | Framework PHP |
| **Filament** | 4.x | Panneau d'administration (forms, tables, relation managers) |
| **Shield** | 4.x (bezhanSalleh/filament-shield) | RBAC : génération GUI des politiques/permissions via spatie |
| **spatie/laravel-permission** | ~6 | RBAC sous-jacent (rôles, permissions, policies) |
| **MariaDB** | ~10+ | Base de données (MySQL-compatible) |
| **barryvdh/laravel-dompdf** | ~3 | Génération PDF des factures, bons de livraison, avoirs |
| **simplesoftwareio/simple-qrcode** | ~2 | Génération de QR codes (produits physiques) |
| **picqer/php-barcode-generator** | ~3 | Génération de codes-barres (produits physiques) |
| **Laravel Sanctum** | ~4 | Authentification API par tokens |
| **intl (PHP extension)** | — | `NumberFormatter::SPELLOUT` pour montants en lettres |
| **html5-qrcode** | UMD via `public/vendor/` | Scan QR côté mobile (Vite/rolldown non compatible Android/Termux) |

---

## 2. Structure du Projet

```
nexus-erp-laravel/
├── app/
│   ├── Enums/                    # 27 enums (backed string, HasLabel)
│   ├── Filament/
│   │   ├── Pages/                # Parametres, ScanStation
│   │   └── Resources/            # 39 resources + Schemas/ + Tables/ + RelationManagers/ + Pages/
│   ├── Helpers/                  # NumberToWords, BadgeColors
│   ├── Http/
│   │   └── Middleware/           # TrustProxies (HTTPS derrière proxy Python)
│   ├── Models/                   # 40 Eloquent models (1 User + 39 métier)
│   ├── Observers/                # 8 observers (5 initiaux + 3 ligne-observers)
│   ├── Providers/                # AppServiceProvider (enregistre observers)
│   └── Services/                 # 7 services + PrefixeDocument enum
├── bootstrap/app.php             # withRouting(api:) déclaré (Laravel 11+)
├── config/                       # auth, filament, permission, dompdf, etc.
├── database/
│   ├── migrations/               # 8 migrations
│   └── seeders/                  # DatabaseSeeder + DemoDataSeeder
├── public/
│   └── vendor/                   # html5-qrcode.min.js (UMD, contournement Vite)
├── resources/views/
│   ├── filament/pages/           # parametres.blade.php, scan-station.blade.php
│   └── pdf/                      # facture.blade.php, bon-livraison.blade.php, avoir.blade.php
├── routes/
│   ├── api.php                   # API mobile v1 (Sanctum)
│   └── web.php                   # Routes Web (Filament, PDF)
└── scripts/                      # Scripts de génération et utilitaires
```

---

## 3. Base de Données

**Base** : `nexus_erp_laravel` (MariaDB, root@127.0.0.1:3306, sans mot de passe)

**Convention** : colonnes en `snake_case` (Laravel). Pas d'enums MySQL — colonnes `string(50)` avec validation au niveau PHP.

### 3.1 Tables (39 tables métier + 5 tables système)

#### Tables système
| Table | Description |
|-------|-------------|
| `users` | Utilisateurs (extends Authenticatable, trait HasRoles spatie) |
| `cache`, `cache_locks` | Cache Laravel |
| `jobs`, `job_batches`, `failed_jobs` | Queue |
| `personal_access_tokens` | Tokens Sanctum |
| `model_has_roles`, `model_has_permissions`, `role_has_permissions`, `roles`, `permissions` | spatie/laravel-permission (Shield) |

#### Tables métier (39 tables créées par la migration `2025_01_01_000010_create_nexus_tables.php`)

| Table | Description | FK clés |
|-------|-------------|---------|
| `clients` | Clients (raison_sociale, nif, email, téléphone, adresse, plafond_credit, solde, actif) | — |
| `produits` | Produits finis (nom, reference, categorie, prix_vente, `taux_tva` 19% défaut, stock_alerte_min, actif) | — |
| `etapes_production` | Étapes de fabrication (nom, ordre, actif) | — |
| `produit_etapes` | Lien produit ↔ étape (avec temps moyen) | produit_id, etape_id |
| `matieres_premieres` | Matières premières (nom, code, unite, cout_unitaire_moyen, actif) | — |
| `produit_matiere_premiere` | Lien produit ↔ matière première (quantité) | produit_id, matiere_premiere_id |
| `depots` | Dépôts (code, nom, type, adresse, actif) | — |
| `hangars` | Zones dans un dépôt (nom, actif) | depot_id |
| `emplacements` | Emplacements dans un hangar | hangar_id |
| `stock_matieres_premieres` | Stock matières par dépôt | matiere_premiere_id, depot_id |
| `sequences` | Compteurs de numérotation (prefixe, annee, dernier_numero) | — |
| `bons_commande` | Bons de commande (numero_bc, client, date, montants, statut, origine…) | client_id |
| `bon_commande_lignes` | Lignes de BC | bon_commande_id, produit_id |
| `ordres_production` | Ordres de fabrication (numero_op, BC origine, depot, dates, statut, priorite, origine) | bon_commande_id, depot_matiere_id |
| `ordre_production_lignes` | Lignes d'OP (produit, quantites) | ordre_production_id, produit_id |
| `lots` | Lots de production (numero_lot, OP, statut, dates) | ordre_production_id |
| `lot_produits` | Lien lot ↔ ligne OP (quantites theorique/produite/rebutee) | lot_id, ordre_production_ligne_id, produit_id |
| `lot_consommation_matiere` | Consommation matière par lot | lot_id, matiere_premiere_id |
| `produits_physiques` | Exemplaires unitaires (code_affiche, produit, lot, statut, emplacement, cree_par) | produit_id, lot_id, lot_produit_id, emplacement_id, etape_actuelle_id |
| `produit_physique_historique` | Historique des mouvements physiques | produit_physique_id |
| `documents_sortie` | Bons de livraison/enlèvement (numero, type, client, BC, statut) | client_id, bon_commande_id |
| `document_sortie_lignes` | Lignes DS (produit physique) | document_sortie_id, produit_physique_id |
| `factures` | Factures (numero_facture, client, BC, montants HT/TVA/TTC, taux_tva, ttc_lettres, mode_reglement, statut, emission) **→ colonnes TVA ajoutées** | client_id, bon_commande_id |
| `facture_lignes` | Lignes de facture (HT seulement) | facture_id, produit_id |
| `avoirs` | Avoirs (numero_avoir, facture d'origine, client, montants HT/TVA/TTC, ttc_lettres, statut) **→ colonnes TVA ajoutées** | facture_id, client_id |
| `avoir_lignes` | Lignes d'avoir (HT seulement) | avoir_id, produit_id |
| `paiements` | Paiements enregistrés | client_id |
| `paiement_imputations` | Imputation paiement ↔ facture | paiement_id, facture_id |
| `mouvements_solde_client` | Historique du solde client | client_id |
| `mouvements_stock_matiere` | Mouvements de stock matière | matiere_premiere_id, depot_id |
| `retours_clients` | Retours clients | document_sortie_id, client_id |
| `retour_client_lignes` | Lignes de retour | retour_client_id, produit_physique_id |
| `bons_transfert` | Bons de transfert inter-dépôts | depot_source_id, depot_destination_id |
| `bon_transfert_lignes` | Lignes de transfert | bon_transfert_id |
| `defauts_production` | Défauts de production | ordre_production_id, produit_id |
| `inventaires` | Sessions d'inventaire | depot_id |
| `inventaire_lignes` | Lignes d'inventaire | inventaire_id, produit_id |
| `inventaire_produits_physiques` | Produits physiques comptés | inventaire_ligne_id, produit_physique_id |
| `matieres_premieres_prix_historique` | Historique des prix matières | matiere_premiere_id |

### 3.2 Migrations TVA (deux migrations ajoutées)

**`2026_02_01_000001_add_taux_tva_and_lettres_to_factures.php`**
```php
Schema::table('factures', function (Blueprint $table) {
    $table->decimal('taux_tva', 5, 2)->default(19.00)->after('montant_ttc')
        ->comment('Taux de TVA applicable à la facture (éditable, défaut 19%)');
    $table->text('montant_ttc_lettres')->nullable()->after('montant_ttc')
        ->comment('Montant TTC écrit en lettres (norme comptable)');
});
```

**`2026_02_01_000002_add_taux_tva_and_lettres_to_avoirs.php`**
```php
Schema::table('avoirs', function (Blueprint $table) {
    $table->decimal('taux_tva', 5, 2)->default(19.00)->after('montant_ttc')
        ->comment('Taux de TVA applicable à l\'avoir (éditable, défaut 19%)');
    $table->text('montant_ttc_lettres')->nullable()->after('montant_ttc')
        ->comment('Montant TTC écrit en lettres (norme comptable)');
});
```

---

## 4. Modèles (40 Eloquent Models)

Tous dans `app/Models/`, extends `Illuminate\Database\Eloquent\Model`, trait `HasFactory`.

### Liste complète

| # | Modèle | Table | Relations principales |
|---|--------|-------|----------------------|
| 1 | `User` | `users` | HasRoles, actif, telephone, poste |
| 2 | `Client` | `clients` | bonCommandes, factures, avoirs, documentsSorties, retoursClients, mouvementsSolde, paiements |
| 3 | `Produit` | `produits` | bonCommandeLignes, factureLignes, avoirLignes, ordreProductionLignes, etapes, matieres, produitsPhysiques |
| 4 | `EtapeProduction` | `etapes_production` | produitEtapes |
| 5 | `ProduitEtape` | `produit_etapes` | produit, etape |
| 6 | `MatierePremiere` | `matieres_premieres` | produitMatieres, stockMatieres, lotConsommations, prixHistoriques |
| 7 | `ProduitMatierePremiere` | `produit_matiere_premiere` | produit, matierePremiere |
| 8 | `Depot` | `depots` | hangars, stockMatieres |
| 9 | `Hangar` | `hangars` | depot, emplacements |
| 10 | `Emplacement` | `emplacements` | hangar, produitsPhysiques |
| 11 | `StockMatierePremiere` | `stock_matieres_premieres` | matierePremiere, depot |
| 12 | `Sequence` | `sequences` | — |
| 13 | `BonCommande` | `bons_commande` | client, `bonCommandeLignes()`, `lignes()` (alias Prisma), factures, ordresProductions, documentsSorties |
| 14 | `BonCommandeLigne` | `bon_commande_lignes` | bonCommande, produit |
| 15 | `OrdreProduction` | `ordres_production` | bonCommande, depotMatiere, `ordreProductionLignes()`, lots |
| 16 | `OrdreProductionLigne` | `ordre_production_lignes` | ordreProduction, produit, lotProduits |
| 17 | `Lot` | `lots` | ordreProduction, `lotProduits()`, lotConsommationMatieres |
| 18 | `LotProduit` | `lot_produits` | lot, ordreProductionLigne, produit, produitsPhysiques |
| 19 | `LotConsommationMatiere` | `lot_consommation_matiere` | lot, matierePremiere |
| 20 | `ProduitPhysique` | `produits_physiques` | produit, lot, lotProduit, emplacement, etapeActuelle, historique |
| 21 | `ProduitPhysiqueHistorique` | `produit_physique_historique` | produitPhysique |
| 22 | `DocumentsSortie` | `documents_sortie` | bonCommande, client, `documentSortieLignes()`, `lignes()` (alias Prisma), retoursClients |
| 23 | `DocumentSortieLigne` | `document_sortie_lignes` | documentSortie, produitPhysique |
| 24 | `Facture` | `factures` | client, bonCommande, `factureLignes()`, avoirs, paiementImputations. **Attributs** : taux_tva=19.00, casts decimals |
| 25 | `FactureLigne` | `facture_lignes` | facture, produit |
| 26 | `Avoir` | `avoirs` | facture, client, `avoirLignes()`. **Attributs** : taux_tva=19.00, casts decimals |
| 27 | `AvoirLigne` | `avoir_lignes` | avoir, produit |
| 28 | `Paiement` | `paiements` | client, imputations |
| 29 | `PaiementImputation` | `paiement_imputations` | paiement, facture |
| 30 | `MouvementSoldeClient` | `mouvements_solde_client` | client |
| 31 | `MouvementStockMatiere` | `mouvements_stock_matiere` | matierePremiere, depot |
| 32 | `RetourClient` | `retours_clients` | documentSortie, client, lignes |
| 33 | `RetourClientLigne` | `retour_client_lignes` | retourClient, produitPhysique |
| 34 | `BonTransfert` | `bons_transfert` | depotSource, depotDestination, lignes |
| 35 | `BonTransfertLigne` | `bon_transfert_lignes` | bonTransfert, produitPhysique |
| 36 | `DefautsProduction` | `defauts_production` | ordreProduction, produit |
| 37 | `Inventaire` | `inventaires` | depot, lignes |
| 38 | `InventaireLigne` | `inventaire_lignes` | inventaire, produit, produitsPhysiques |
| 39 | `InventaireProduitPhysique` | `inventaire_produits_physiques` | inventaireLigne, produitPhysique |
| 40 | `MatierePremierePrixHistorique` | `matieres_premieres_prix_historique` | matierePremiere |

### 4.1 Alias de relations `lignes()` (ajout pour conformité Prisma)

Pour que le `FactureService` utilise le même nom de relation que l'original Prisma (`lignes`), deux alias ont été ajoutés :

**`BonCommande::lignes()`** — alias vers `bonCommandeLignes()`
```php
public function lignes(): HasMany
{
    return $this->hasMany(\App\Models\BonCommandeLigne::class);
}
```

**`DocumentsSortie::lignes()`** — alias vers `documentSortieLignes()`
```php
public function lignes(): HasMany
{
    return $this->hasMany(\App\Models\DocumentSortieLigne::class, 'document_sortie_id');
}
```

---

## 5. Enums (27 Backed String Enums)

Tous dans `app/Enums/`, implémentent `Filament\Support\Contracts\HasLabel`.

| # | Enum | Valeurs (string) |
|---|------|-------------------|
| 1 | `DecisionDefaut` | `rebute`, `retouche` |
| 2 | `DecisionRetour` | `remplacement`, `remboursement`, `reparation` |
| 3 | `EtatProduit` | `stock`, `production`, `reserve`, `expedie`, `defectueux` |
| 4 | `GraviteDefaut` | `mineur`, `majeur`, `critique` |
| 5 | `MethodeScan` | `qr`, `barcode`, `manuel` |
| 6 | `ModePaiement` | `espece`, `cheque`, `virement`, `carte`, `traite`, `prelevement` |
| 7 | `MotifAvoir` | `retour`, `remise`, `commercial`, `erreur`, `autre` |
| 8 | `OrigineOrdreProduction` | `commande`, `stock` |
| 9 | `ResultatInventairePhysique` | `conforme`, `excedent`, `manquant`, `endommage` |
| 10 | `StatutBonCommande` | `devis`, `brouillon`, `confirmee`, `en_production`, `partiellement_prete`, `prete`, `partiellement_livree`, `livree`, `annulee` |
| 11 | `StatutBonTransfert` | `brouillon`, `valide`, `en_cours`, `effectue`, `annule` |
| 12 | `StatutClient` | `actif`, `inactif`, `suspendu` |
| 13 | `StatutDefaut` | `declare`, `analyse`, `corrige`, `valide` |
| 14 | `StatutDocumentSortie` | `prepare`, `effectue`, `annule` |
| 15 | `StatutFacture` | `brouillon`, `emise`, `payee`, `partiellement_payee`, `impayee`, `annulee` |
| 16 | `StatutInventaire` | `brouillon`, `en_cours`, `valide`, `annule` |
| 17 | `StatutLot` | `en_attente`, `en_production`, `partiel`, `termine`, `termine_avec_rebut`, `rebute`, `annule` |
| 18 | `StatutLotProduit` | `en_attente`, `en_production`, `termine`, `rebute` |
| 19 | `StatutOrdreProduction` | `brouillon`, `confirme`, `en_cours`, `termine`, `annule` |
| 20 | `StatutPaiement` | `attente`, `encaisse`, `refuse`, `annule` |
| 21 | `StatutProduitPhysique` | `en_production`, `disponible`, `reserve`, `vendu`, `livre`, `defectueux`, `rebut`, `retourne`, `annule` |
| 22 | `StatutRetour` | `en_attente`, `recu`, `inspecte`, `cloture`, `refuse` |
| 23 | `TypeDepot` | `matiere`, `produit_fini`, `mixte` |
| 24 | `TypeDocumentSortie` | `livraison`, `enlevement` |
| 25 | `TypeInventaire` | `periodique`, `tournant`, `exceptionnel` |
| 26 | `TypeMouvementPhysique` | `entree`, `sortie`, `transfert` |
| 27 | `TypeMouvementStockMatiere` | `entree`, `sortie`, `ajustement`, `consommation`, `retour` |

---

## 6. Services (7 + PrefixeDocument)

### 6.1 `PrefixeDocument` — Enum des préfixes de numérotation
```
OP='op', LOT='lot', BC='bc', BL='ds', BE='be', FAC='facture',
AV='avoir', PAIE='paie', BT='bt', INV='inv', RET='ret', TRF='trf', PHY='phy'
```
`formatNumero('ds', 2026, 1)` → `BL-2026-000001` (spécial DS → BL).

### 6.2 `SequenceService`
- `prochainNumero(PrefixeDocument, int $annee): int` — UPDATE row + 1, retourne l'ancien + 1
- `formatNumero(string $prefixe, int $annee, int $n, int $pad=6): string` — formate en `PREFIX-ANNEE-NNNNNN`

### 6.3 `CommandeService`
- `creerDevis(array $data): BonCommande` — crée un BC « dévis » avec lignes, numérotation, montant HT
- `confirmerDevis(int $bcId): BonCommande` — change statut en `confirmee`
- Implique `SequenceService::prochainNumero(PrefixeDocument::BC, …)`

### 6.4 `FactureService`
- **`creerFactureDepuisDocuments(int $clientId, array $documentSortieIds, int $userId): Facture`** — Crée une facture brouillon à partir de documents de sortie. Regroupe les produits physiques (qte = nombre d'exemplaires de chaque produit), calcule HT = somme des lignes, TVA = HT × 19%, TTC = HT + TVA, lettres. Utilise `$doc->lignes` (alias Prisma).
- **`emettreFacture(int $factureId, int $userId): Facture`** — Émet la facture : numérotation via FAC (SequenceService), statut → `emise`, crée MouvementSoldeClient, met à jour solde client.
- **`imputerPaiement(int $paiementId, array $imputations)`** — Impute un paiement sur une ou plusieurs factures, gère les statuts `payee` / `partiellement_payee` / `emise`, et le solde client.

### 6.5 `ProductionService`
- Gère le cycle de production : création d'OP, lancement, suivi, clôture.

### 6.6 `StockMatiereService`
- Mouvements de stock matières premières : entrée, sortie, ajustement, consommation, retour.

### 6.7 `BarcodeService`
- Génération de codes-barres (via picqer/php-barcode-generator) pour les étiquettes produits.

### 6.8 `PdfService`
- Génération de PDF via dompdf :
  - **`facture(Facture $f)`** → vue `pdf/facture.blade.php` + route `factures.pdf`
  - **`bonLivraison(DocumentsSortie $ds)`** → vue `pdf/bon-livraison.blade.php`
  - **`avoir(Avoir $a)`** → vue `pdf/avoir.blade.php` + route `avoirs.pdf`

---

## 7. Observateurs (8)

Enregistrés dans `AppServiceProvider::boot()` :

| Observateur | Modèle | Déclencheur | Action |
|------------|--------|-------------|--------|
| `ClientObserver` | Client | `creating` | Valeurs par défaut (solde, plafond) |
| `ProduitPhysiqueObserver` | ProduitPhysique | `creating` | `statut='en_production'` si null, `date_creation=now()` |
| `BonCommandeObserver` | BonCommande | `saved` | Recalcule montants si des lignes existent (appel statique `recompute()`) — **early return si pas de lignes** (permet à `creerDevis` de passer des montants explicites) |
| `BonCommandeLigneObserver` | BonCommandeLigne | `saved`, `deleted` | Recalcule le BC parent via `BonCommandeObserver::recompute()` |
| `FactureObserver` | Facture | `saved` | Recalcule HT = Σ lignes, TVA = HT × taux_tva / 100, TTC = HT + TVA, TTC lettres |
| `FactureLigneObserver` | FactureLigne | `saved`, `deleted` | Recalcule la facture parente via `FactureObserver::recompute()` |
| `AvoirObserver` | Avoir | `saved` | Recalcule HT = Σ lignes, TVA = HT × taux_tva / 100, TTC = HT + TVA, TTC lettres |
| `AvoirLigneObserver` | AvoirLigne | `saved`, `deleted` | Recalcule l'avoir parent via `AvoirObserver::recompute()` |

### Règle de recalcul TVA (appliquée aux 3 observateurs de documents)

```
HT    = Σ des montants_total des lignes       ← lignes HT seulement
TVA   = HT × taux_tva / 100                   ← taux éditable, défaut 19 %
TTC   = HT + TVA
TTC lettres = enLettres(TTC)                  ← via NumberFormatter fr_FR SPELLOUT
```

Si aucune ligne n'existe → pas de recalcul (on préserve les valeurs fournies).

### Comportement « saveQuietly » dans les observateurs

Pour éviter les boucles infinies (observer → save → observer → …), les observateurs (FactureObserver, AvoirObserver, BonCommandeObserver) utilisent `saveQuietly()` dans leur `recompute()` — la sauvegarde des montants se fait sans rédéclencher l'événement `saved`.

---

## 8. Ressources Filament (44 resources, 39 entités visibles)

### 8.1 Navigation Groups

| Groupe | Resources | Visibles |
|--------|-----------|----------|
| **Commercial** | Clients, Bons de commande, Mouvements solde client | 3 |
| **Production** | Ordres de production, Lots, Lot produits, Lot consommation matières, Produits physiques, Produit physique historique, Défauts de production, Étapes de production, Produit étapes, Produit matières premières, Ordre production lignes | 11 (7 visibles + 4 lignes cachées) |
| **Produits** | Produits | 1 |
| **Documents de sortie** | Documents de sortie, Document sortie lignes | 2 (lignes cachée) |
| **Facturation** | Factures, Facture lignes, Avoirs, Avoir lignes | 4 (lignes cachées) |
| **Paiements** | Paiements, Paiement imputations | 2 (imputations cachée) |
| **Stock** | Dépôts, Hangars, Emplacements, Matières premières, Stock matières premières, Mouvements stock matières, Matières premières prix historique, Inventaires, Inventaire lignes, Inventaire produits physiques, Bons de transfert, Bon transfert lignes | 12 (6 lignes cachées) |
| **Retours clients** | Retours clients, Retour client lignes | 2 (lignes cachée) |
| **Paramètres** | Sequences | 1 |
| **Scan** | *(ScanStation — page non groupée)* | 0 |
| **Utilisateurs** | Users | 1 |

**Total** : 44 resources déclarées, 24 cachées du menu (gérées via relation managers), **20 visibles dans la navigation** + 2 pages (Paramètres, ScanStation).

### 8.2 Resources principales avec relation managers

| Resource | Relation Managers |
|----------|-------------------|
| `FactureResource` | `FactureLignesRelationManager` |
| `AvoirResource` | `AvoirLignesRelationManager` |
| `BonCommandeResource` | `BonCommandeLignesRelationManager` |
| `DocumentsSortieResource` | `DocumentSortieLignesRelationManager` |
| `OrdreProductionResource` | `OrdreProductionLignesRelationManager` |
| `LotResource` | `LotProduitsRelationManager`, `LotConsommationMatieresRelationManager` |
| `ProduitResource` | `ProduitEtapesRelationManager`, `ProduitMatierePremieresRelationManager` |
| `ProduitPhysiqueResource` | (via relation) |
| `ClientResource` | (via relation) |
| `DepotResource` | `HangarsRelationManager` |

### 8.3 UsersResource

- Groupe : **Utilisateurs**
- Champs : `name`, `email` (unique), `password` (required on create), `actif` (toggle), `telephone`, `poste`
- Liaison directe aux rôles : `Select::make('roles')->multiple()->relationship('roles','name')->preload()`
- Table : name, email, actif, roles

### 8.4 Paramètres Page

- Groupe : **Paramètres**
- Cards fidèles à l'original Next.js :
  - **Dépôts & Stock** : Dépôts, Matières premières, Produits finis
  - **Commercial** : Clients actifs, Étapes de production, Utilisateurs actifs
  - **Système** : Version `Nexus ERP v5.1.1`, Stack `Laravel 13 + Filament 4 + Shield`, Base `MariaDB / MySQL`, Devise `DZD (Algérien)`

---

## 9. Amélioration TVA — Design Comptable Complet

### 9.1 Principe (décision métier, confirmée par l'utilisateur)

> **Les lignes de facture/avoir sont HT uniquement. Le taux de TVA est global et éditable sur le document.**

Ce choix dévie du modèle original (qui avait une TVA par ligne produit) pour respecter une norme comptable plus simple et plus courante en ALGÉRIE.

### 9.2 Ce qui a changé

#### Modèles
- `Facture` : ajout colonnes `taux_tva` (decimal 5,2, default 19.00), `montant_ttc_lettres` (text, nullable)
- `Avoir` : ajout colonnes `taux_tva` (decimal 5,2, default 19.00), `montant_ttc_lettres` (text, nullable)
- `Produit` : conserve `taux_tva` (original, non utilisé dans le calcul document — gardé pour compatibilité)

#### Migrations
- `2026_02_01_000001_add_taux_tva_and_lettres_to_factures.php`
- `2026_02_01_000002_add_taux_tva_and_lettres_to_avoirs.php`

#### Attributs par défaut sur les modèles
```php
protected $attributes = ['taux_tva' => 19.00];
```

#### Observateurs — logique `recompute()`
```php
HT    = (float) $facture->lignes()->sum('montant_total');  // HT uniquement
TVA   = HT * $taux / 100;                                   // taux est (float)$facture->taux_tva
TTC   = HT + TVA;
lettres = \App\Helpers\NumberToWords::enLettres($ttc);
```
Si pas de lignes → early return (pas de recalcul).

#### FactureService::creerFactureDepuisDocuments
- Lignes générées avec `montant_total` = qte × prix_unitaire (HT)
- Recalcul après création : `$taux = 19.0; $tva = $montantHt * $taux / 100; $ttc = $montantHt + $tva;`
- `montant_ttc_lettres` écrit via `NumberToWords::enLettres($ttc)`

#### FactureService::emettreFacture
- Émet avec numérotation FAC, créé MouvementSoldeClient, met à jour solde client.

#### Formulaires Filament

**FactureForm** — Section « Montants » :
- `montant_ht` (disabled, dehydrated: false) → « Total HT (somme des lignes) »
- `taux_tva` (editable, default 19, suffix %) → « Taux de TVA »
- `montant_tva` (disabled, dehydrated: false) → « Montant TVA »
- `montant_ttc` (disabled, dehydrated: false) → « Total TTC »
- `montant_ttc_lettres` (disabled, dehydrated: false, columnSpanFull) → « Total TTC en lettres »
- `mode_reglement` (Select, options ModePaiement, default 'virement') → « Mode de règlement »
- `montant_paye` (disabled, dehydrated: false) → « Déjà payé »
- `remise` (numeric, default 0) → « Remise »

**AvoirForm** — Section « Montants » (identique sans `mode_reglement` ni `montant_paye`) :
- `montant_ht` (disabled), `taux_tva` (editable 19%), `montant_tva` (disabled), `montant_ttc` (disabled), `montant_ttc_lettres` (disabled)
- `valide_par` (disabled)

#### Tables Filament

**FacturesTable** : colonnes incluant `montant_ht`, `montant_tva`, **`taux_tva` (label "TVA %")**, `montant_ttc`, `montant_paye`, `remise`, `statut` (badge). Action PDF.

**AvoirsTable** : colonnes incluant `taux_tva`.

#### PDF facture

Le template `pdf/facture.blade.php` affiche :
```
Total HT (somme des lignes)     =>  montant_ht
TVA (XX.XX %)                   =>  montant_tva
Total TTC                       =>  montant_ttc
Déjà payé                       =>  montant_paye
Net à payer                     =>  TTC - payé
Montant TTC en lettres          =>  montant_ttc_lettres
Mode de règlement               =>  mode_reglement
```

#### PDF avoir

Le template `pdf/avoir.blade.php` affiche le même bloc montants (HT → TVA → TTC → lettres).

### 9.3 Helper `NumberToWords`

`app/Helpers/NumberToWords.php` — convertit un montant numérique en lettres via l'extension **intl** :

```php
$fmt = new NumberFormatter('fr_FR', NumberFormatter::SPELLOUT);
$entier = (int) floor($montant);
$mots = $fmt->format($entier) . ' dinars';
```

Format : « Un million quatre cent quatre mille deux cents dinars ». Gère les centimes (arrondi à 2 décimales) avec « dinars et X centimes ».

---

## 10. PDF (3 modèles)

### 10.1 Facture
- Route : `GET /admin/factures/{facture}/pdf` (name: `factures.pdf`)
- Contrôleur/Service : `PdfService::facture(Facture $f)`
- Vue : `resources/views/pdf/facture.blade.php`
- Contenu : En-tête NEXUS ERP, infos client, infos document, tableau des lignes (Désignation, Qté, PU HT, Montant), totaux (HT, TVA avec taux%, TTC, déjà payé, net à payer), TTC en lettres, mode de règlement, notes.

### 10.2 Bon de livraison
- Route : `GET /admin/documents-sorties/{ds}/pdf` (name: `documents-sorties.pdf`)
- Contrôleur/Service : `PdfService::bonLivraison(DocumentsSortie $ds)`
- Vue : `resources/views/pdf/bon-livraison.blade.php`

### 10.3 Avoir
- Route : `GET /admin/avoirs/{avoir}/pdf` (name: `avoirs.pdf`)
- Contrôleur/Service : `PdfService::avoir(Avoir $a)`
- Vue : `resources/views/pdf/avoir.blade.php`

---

## 11. QR Scan (Tracabilité Produits Physiques)

### 11.1 Problème Vite/rolldown
Vite 8 / rolldown ne compile pas sur Termux/Android (`@rolldown/binding-android-arm-eabi` manquant).
→ Solution : **UMD build officiel** de `html5-qrcode` servit depuis `public/vendor/html5-qrcode.min.js` au lieu d'un bundle Vite.

### 11.2 Page ScanStation
- `app/Filament/Pages/ScanStation.php` — page Filament avec vue Blade
- `resources/views/filament/pages/scan-station.blade.php` — interface scanner QR
- `public/js/scan-station.js` — scripts de scan (initialisation html5-qrcode, lookup API `/api/v1/produits-physiques/lookup?code=...`)
- Nécessite un contexte sécurisé (HTTPS ou `localhost`/LAN) pour `getUserMedia` → middleware TrustProxies + proxy TLS Python pour tests.

### 11.3 Lookup API
`GET /api/v1/produits-physiques/lookup?code={code_affiche}` (Sanctum) → retourne produit, statut, emplacement, étape, commande.
Implémenté dans `routes/api.php`.

---

## 12. API Mobile v1 (Sanctum)

Routes définies dans `routes/api.php`, chargées via `withRouting(api:)` dans `bootstrap/app.php`.

### Endpoints

| Méthode | Path | Auth | Description |
|---------|------|------|-------------|
| POST | `/api/v1/login` | Non | Login → token + user |
| GET | `/api/v1/me` | Sanctum | Profil utilisateur |
| POST | `/api/v1/logout` | Sanctum | Révoque le token |
| GET | `/api/v1/clients` | Sanctum | Liste clients (recherche q) |
| GET | `/api/v1/produits` | Sanctum | Liste produits (recherche q) |
| GET | `/api/v1/sequence/next?type=bc` | Sanctum | Prochain numéro de document |
| GET | `/api/v1/bons-commande` | Sanctum | Liste BC |
| POST | `/api/v1/bons-commande` | Sanctum | Créer BC (via CommandeService) |
| GET | `/api/v1/produits-physiques/lookup?code=` | Sanctum | Tracabilité QR |

---

## 13. Demo Seeder (Chaîne Complète)

`database/seeders/DemoDataSeeder.php` — 169 lignes, transactionel, **idempotent** (ne s'exécute que si `BonCommande::count() === 0`).

### Chaîne créée :
1. **Bon de commande** `BC-2026-000001` (statut `confirmee`, HT = 5×185000 + 3×85000 = **1 180 000**) — 2 lignes
2. **Ordre de production** `OP-2026-000001` (statut `en_cours`, origine `commande`) — 2 lignes
3. **Lot** `LOT-2026-000001` (statut `en_production`)
4. **LotProduit** (2 entrées : qte théorique 5 et 3, toutes produites)
5. **Produits physiques** (8 unités : statut `disponible`, codes `PHY-2026-0001` à `0008`)
6. **Document de sortie** `BL-2026-000001` (type `livraison`, statut `effectue`) — 8 lignes
7. **Facture** `FACTURE-2026-000001` (statut `emise`, TVA 19%, HT = 1 180 000, TVA = 224 200, TTC = **1 404 200**, lettres « Un million quatre cent quatre mille deux cents dinars »)
8. **Solde client** mis à jour : +1 404 200

### Bouclier idempotent
```php
if (BonCommande::count() > 0) { info('déjà présentes'); return; }
```

### Atomicité
Tout le seeder est encapsulé dans `DB::transaction(function () { … })`. Si une étape échoue (ex : `FactureService::creerFactureDepuisDocuments`), tout est rollbacké → `BonCommande::count()` reste 0 → le re-seed fonctionne.

---

## 14. RBAC (Shield + spatie/laravel-permission)

### Rôles seedés
| Rôle | Description |
|------|-------------|
| `admin` | Accès total |
| `commercial` | Gestion clients, BC, factures |
| `production` | Gestion OP, lots, production |
| `magasinier` | Gestion stocks, dépôts, inventaires |

### Communications Shield
```
php artisan shield:generate --all --panel=admin --option=policies_and_permissions
```
→ **41 politiques, 494 permissions, 43 entités** protégées.

### Liaison utilisateur ↔ rôles
Directe dans le formulaire User : `Select::make('roles')->multiple()->relationship('roles','name')->preload()`.

---

## 15. Corrections & Bugs Résolus

### 15.1 DocumentsSortie vs DocumentSortie
**Bug** : `FactureService::creerFactureDepuisDocuments()` référençait `\App\Models\DocumentSortie` (classe inexistante).
**Fix** : Corrigé en `\App\Models\DocumentsSortie`.

### 15.2 `lignes` relation manquante
**Bug** : `FactureService` utilisait `$doc->bonCommande->lignes` et `$doc->lignes` (noms Prisma) mais ces relations n'existaient pas dans les modèles Laravel.
**Fix** : Ajout des alias `BonCommande::lignes()` et `DocumentsSortie::lignes()`.

### 15.3 BC : observer qui zérotait le montant
**Bug** : `BonCommandeObserver::saved()` recomputait toujours (y compris avant création des lignes), mettant `montant_ht=0` même quand `creerDevis` fournissait un montant explicite.
**Fix** : Early return `if (! $lignes->exists()) return;` + `BonCommandeLigneObserver` pour recalculer le parent quand une ligne est créée/supprimée.

### 15.4 Filament 4 : `Section` import erroné
**Bug** : 3 formulaires (FactureForm, AvoirForm, UsersForm) importaient `Filament\Forms\Components\Section` qui n'existe pas dans Filament 4 (il a migré dans `filament/schemas`).
**Fix** : Remplacé par `Filament\Schemas\Components\Section`.

### 15.5 Vite 8 / rolldown sur Termux
**Problème** : `@rolldown/binding-android-arm-eabi` manquant, Vite ne compile pas.
**Solution** : html5-qrcode servit en UMD depuis `public/vendor/`.

### 15.6 `routes/api.php` non chargé automatiquement
**Problème** : Laravel 11+ n'auto-charge pas `routes/api.php`.
**Solution** : Déclaré via `withRouting(api: __DIR__.'/../routes/api.php')` dans `bootstrap/app.php`.

### 15.7 `Avoir.statut` est un string, pas un enum
**Décision** : L'original n'a pas de `StatutAvoir` enum. L'avoir utilise des options inline dans le formulaire (`brouillon`/`émise`/`validé`/`annulé`), contrairement à Facture qui a un `StatutFacture` enum complet.

### 15.8 `Avoir` n'a pas de `mode_reglement`
**Décision** : Le schéma original n'a pas de colonne `mode_reglement` sur `avoirs`. Les avoirs n'affichent pas ce champ.

---

## 16. GitHub

- **Dépôt** : `https://github.com/rachiddri/nexus-erp-laravel` (public)
- **Branche** : `main`
- **Commits** :
  - `5936a36` Initial commit
  - `970c68b` BC observer fix (montant, ligne observer)
  - `5a11a3e` Demo seed + FactureService DocumentSortie fix
  - `7b65624` Filament 4 Section import → Schemas\Components
- **Push** via curl + PAT GitHub (gh CLI non installé). Token retiré de l'URL distante après push.

---

## 17. Tests & Vérifications

### TVA facture (vérifié)
```
HT = 1 180 000  (5×185000 + 3×85000)
TVA @ 19% = 224 200
TTC = 1 404 200
Lettres = « Un million quatre cent quatre mille deux cents dinars »
Mode de règlement = virement
```

### TVA avoir (vérifié)
```
HT = 370 000
TVA @ 19% = 70 300
TTC = 440 300
Lettres = « Quatre cent quarante mille trois cents dinars »
```

### BC sans lignes (vérifié)
Un BC créé avec `montant_ht=999` sans lignes conserve 999 (observer early return).
Après ajout d'une ligne → HT recalculé.

### API (vérifié)
- `POST /api/v1/login` → token
- `POST /api/v1/bons-commande` → 201 + BC créé
- `GET /api/v1/sequence/next?type=bc` → prochain numéro

---

## 18. Administration (accès)

- **URL** : `http://localhost:8000/admin`
- **Login** : `admin@nexus-erp.dz` / `admin123`
- **Serveur** : tmux session `nexus-laravel`, `php artisan serve --host=0.0.0.0 --port=8000`
- **DB** : MariaDB `nexus_erp_laravel`, root@127.0.0.1, no password

---

## 19. Corrections Futures Possibles (non bloquantes)

1. Reset des compteurs de séquences pour un démarrage frais (actuellement BC = 000001, FAC = 000001, tout est propre après le re-seed).
2. Ajout d'un README.md au dépôt GitHub (actuellement inexistant).
3. Seeder d'avoir + retour client pour boucler la boucle comptable.
4. Internationalisation i18n des enums (labels en arabe ?).
5. Dashboard / statistiques (widgets Filament).
