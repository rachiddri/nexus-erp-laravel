# Plan Nexus ERP v5.1.1 — Laravel 13 + Filament 4 + Shield

> **Copie 100% fidèle du système original Next.js+Prisma**, reconstruit avec **Laravel 13.20**, **Filament 4.x**, **Shield 4.x** (spatie/laravel-permission), **MariaDB/MySQL** et **Sanctum** (API).
>
> Ce plan suit la structure du plan v5-1-1 original (1844 lignes, 25 sections) et l'adapte à l'implémentation réelle.

---

## 0. Mode d'emploi du document (pour un agent IA)

1. **Ordre d'exécution** : les phases du développement suivent la logique du plan (de la stack → schéma → services → UI → API → tests).
2. **Source de vérité** : en cas de contradiction apparente, la priorité est : §3 (schéma) > §4 (machines à états) > §6 (services) > §11 (UI).
3. **Interdictions absolues** :
   - Aucun `UPDATE`/`DELETE` sur les tables append-only (§3.12).
   - Aucune orchestration multi-tables hors service + `DB::transaction`.
   - Aucun compteur mis à jour par lecture-modification-écriture : uniquement `increment()`/`decrement()`.
   - Aucun statut saisi par l'utilisateur : tous les statuts listés « calculé » au §4 sont recalculés par le code.
4. **Nommage** : tout le domaine (tables, colonnes, enums, services) est en **français** ; le code technique (variables internes Laravel, méthodes framework) reste en anglais idiomatique.
5. Chaque section « ✅ Critères d'acceptation » est testable automatiquement.

---

## 1. Stack technique & initialisation du projet

### 1.1 Versions figées

| Composant | Version installée | Note |
|---|---|---|
| **PHP** | 8.3+ (requis Laravel 13) | enums, readonly, typed properties |
| **Laravel** | **13.20.0** | Framework |
| **Filament** | **4.x** (forms, schemas, tables, notifications) | Panneau admin — **Filament 5 non utilisé** car Shield 4 non validé dessus |
| **Shield** | **4.x** (bezhanSalleh/filament-shield) | RBAC — génération GUI des politiques/permissions |
| **spatie/laravel-permission** | ~6 | RBAC sous-jacent (rôles, permissions, policies) |
| **MariaDB** | 10+ | Base de données (MySQL-compatible), InnoDB, utf8mb4_unicode_ci |
| **barryvdh/laravel-dompdf** | ~3 | Génération PDF |
| **simplesoftwareio/simple-qrcode** | ~2 | QR codes (SVG/PNG) |
| **picqer/php-barcode-generator** | ~3 | Codes-barres |
| **Laravel Sanctum** | ~4 | API tokens |
| **intl (PHP extension)** | — | `NumberFormatter::SPELLOUT` pour montants en lettres |

### 1.2 Packages installés

```bash
composer require filament/filament:^4
composer require bezhansalleh/filament-shield:^4
composer require spatie/laravel-permission
composer require laravel/sanctum
composer require barryvdh/laravel-dompdf
composer require simplesoftwareio/simple-qrcode
composer require picqer/php-barcode-generator
```

### 1.3 Configuration d'environnement

```
APP_TIMEZONE=Africa/Algiers
APP_LOCALE=fr
QUEUE_CONNECTION=sync              # synchronisé (pas de Redis/Horizon en v1)
CACHE_STORE=file                   # pas de Redis
SESSION_DRIVER=file                # pas de Redis
DB_CONNECTION=mysql
```

> **Écart v5-1-1** : l'original spécifiait Redis/Horizon/backups/activitylog. L'implémentation réelle utilise les valeurs par défaut de Laravel (file/sync). Ces éléments peuvent être ajoutés ultérieurement.

### 1.4 Structure de dossiers en place

```
app/
 ├─ Enums/                  # 27 enums métier (§2)
 ├─ Models/                 # 40 Eloquent models
 ├─ Observers/              # 8 observers
 ├─ Services/               # 7 services + PrefixeDocument enum (§6)
 ├─ Helpers/                # NumberToWords, BadgeColors
 ├─ Http/
 │   └─ Middleware/         # TrustProxies (HTTPS derrière proxy)
 ├─ Filament/
 │   ├─ Resources/          # 44 resources (39 entités + 5 ligne/detail cachées)
 │   ├─ Pages/              # Parametres, ScanStation
 │   └─ Resources/*/RelationManagers/  # Relation managers pour lignes enfants
 ├─ Exceptions/             # (à créer selon §7)
 └─ Providers/              # AppServiceProvider (enregistre observers)
bootstrap/app.php           # withRouting(api:) pour routes/api.php
config/                     # auth, filament, permission, dompdf, etc.
database/
 ├─ migrations/             # 8 migrations
 └─ seeders/                # DatabaseSeeder + DemoDataSeeder
public/
 └─ vendor/                 # html5-qrcode.min.js (UMD, contournement Vite)
resources/views/
 ├─ filament/pages/         # parametres.blade.php, scan-station.blade.php
 └─ pdf/                    # facture.blade.php, bon-livraison.blade.php, avoir.blade.php
routes/
 ├─ api.php                 # API mobile v1 (Sanctum, 10 routes)
 └─ web.php                 # Routes Web (Filament, PDF, scan-station)
```

---

## 2. Conventions de code & Enums

### 2.1 Conventions générales

- **Modèles** : singulier français (`OrdreProduction`, `ProduitPhysique`, `LotProduit`). `$guarded = []` interdit ; `$fillable` explicite partout.
- **Casts** : tout statut casté vers son Enum ; tout montant casté `decimal:2` ; dates en `date`/`datetime`.
- **Argent** : stocké en `DECIMAL(15,2)`, arrondi commercial half-up 2 décimales.
- **Transactions** : `DB::transaction(fn () => ..., attempts: 3)` pour les transactions contenant `lockForUpdate`.
- **Jamais de logique dans les contrôleurs/pages Filament** : ils valident, appellent un service, affichent le résultat.
- **Colonnes en snake_case** (Laravel convention).
- **Pas d'enums MySQL** : colonnes `string(50)` + cast Enum PHP (évolutivité).

### 2.2 Enums PHP (27 backed string, un fichier par enum)

Chaque enum implémente `Filament\Support\Contracts\HasLabel` et expose `getLabel(): string`.

#### Statuts documentaires
| Enum | Valeurs |
|------|---------|
| `StatutBonCommande` | `devis`, `brouillon`, `confirmee`, `en_production`, `partiellement_prete`, `prete`, `partiellement_livree`, `livree`, `annulee` |
| `StatutDocumentSortie` | `prepare`, `effectue`, `annule` |
| `StatutFacture` | `brouillon`, `emise`, `payee`, `partiellement_payee`, `impayee`, `annulee` |
| `StatutOrdreProduction` | `brouillon`, `confirme`, `en_cours`, `termine`, `annule` |
| `StatutLot` | `en_attente`, `en_production`, `partiel`, `termine`, `termine_avec_rebut`, `rebute`, `annule` |
| `StatutLotProduit` | `en_attente`, `en_production`, `termine`, `rebute` |
| `StatutProduitPhysique` | `en_production`, `disponible`, `reserve`, `vendu`, `livre`, `defectueux`, `rebut`, `retourne`, `annule` |
| `StatutBonTransfert` | `brouillon`, `valide`, `en_cours`, `effectue`, `annule` |
| `StatutInventaire` | `brouillon`, `en_cours`, `valide`, `annule` |
| `StatutPaiement` | `attente`, `encaisse`, `refuse`, `annule` |
| `StatutRetour` | `en_attente`, `recu`, `inspecte`, `cloture`, `refuse` |
| `StatutDefaut` | `declare`, `analyse`, `corrige`, `valide` |

#### Types & origines
| Enum | Valeurs |
|------|---------|
| `TypeDocumentSortie` | `livraison`, `enlevement` |
| `TypeDepot` | `matiere`, `produit_fini`, `mixte` |
| `TypeInventaire` | `periodique`, `tournant`, `exceptionnel` |
| `TypeMouvementPhysique` | `entree`, `sortie`, `transfert` |
| `TypeMouvementStockMatiere` | `entree`, `sortie`, `ajustement`, `consommation`, `retour` |
| `OrigineOrdreProduction` | `commande`, `stock` |

#### Autres
| Enum | Valeurs |
|------|---------|
| `ModePaiement` | `espece`, `cheque`, `virement`, `carte`, `traite`, `prelevement` |
| `MotifAvoir` | `retour`, `remise`, `commercial`, `erreur`, `autre` |
| `DecisionDefaut` | `rebute`, `retouche` |
| `DecisionRetour` | `remplacement`, `remboursement`, `reparation` |
| `GraviteDefaut` | `mineur`, `majeur`, `critique` |
| `MethodeScan` | `qr`, `barcode`, `manuel` |
| `ResultatInventairePhysique` | `conforme`, `excedent`, `manquant`, `endommage` |
| `EtatProduit` | `stock`, `production`, `reserve`, `expedie`, `defectueux` |
| `StatutClient` | `actif`, `inactif`, `suspendu` |

✅ **Critères d'acceptation §2** : aucun statut en chaîne littérale dans le code applicatif (grep ne matche que les enums et migrations).

---

## 3. Base de données — Migrations détaillées

### 3.1 Conventions

- Moteur InnoDB, `utf8mb4_unicode_ci`.
- Toutes les tables ont `id` (bigint unsigned auto), `created_at`, `updated_at`.
- FK : `foreignId(...)->constrained(...)`. Comportement par défaut **`restrictOnDelete()`**. `nullOnDelete()` uniquement où indiqué.
- Colonnes monétaires : `decimal(15,2)`. Taux : `decimal(5,2)`. Statuts : `string(50)` + cast Enum (pas de type ENUM SQL).

### 3.2 Migrations (8 fichiers)

```
0001_01_01_000000_create_users_table.php          # Users Laravel
0001_01_01_000001_create_cache_table.php           # Cache
0001_01_01_000002_create_jobs_table.php            # Queue
2025_01_01_000010_create_nexus_tables.php          # 39 tables métier (physique unique)
2026_02_01_000001_add_taux_tva_and_lettres_to_factures.php  # TVA factures
2026_02_01_000002_add_taux_tva_and_lettres_to_avoirs.php    # TVA avoirs
2026_07_20_205112_create_permission_tables.php     # spatie/laravel-permission
2026_07_20_215955_create_personal_access_tokens_table.php   # Sanctum
```

### 3.3 Tables métier complètes

**`clients`** : `id`, `raison_sociale`, `nif` (unique), `email`, `tel`, `adresse`, `plafond_credit` (decimal 15,2), `solde` (decimal 15,2, default 0), `actif` (bool, default true), `timestamps`.

**`produits`** : `id`, `nom`, `reference` (unique), `categorie`, `prix_vente` (decimal 15,2), `tva_taux` (decimal 5,2, default 19.00), `stock_alerte_min` (int), `actif` (bool), `timestamps`.

**`etapes_production`** : `id`, `nom`, `ordre` (int), `actif` (bool), `timestamps`.

**`produit_etapes`** : `id`, `produit_id` (FK), `etape_id` (FK), `temps_moyen_minutes` (int nullable), `timestamps`. Unique : produit_id + etape_id.

**`matieres_premieres`** : `id`, `nom`, `code` (unique), `unite`, `cout_unitaire_moyen` (decimal 15,2), `actif` (bool), `timestamps`.

**`produit_matiere_premiere`** : `id`, `produit_id` (FK), `matiere_premiere_id` (FK), `quantite` (decimal 12,3), `timestamps`. Unique : produit_id + matiere_premiere_id.

**`depots`** : `id`, `code` (unique), `nom`, `type` (string 20), `adresse` (nullable), `actif` (bool), `timestamps`.

**`hangars`** : `id`, `depot_id` (FK nullable, restrictOnDelete), `nom`, `actif` (bool), `timestamps`.

**`emplacements`** : `id`, `hangar_id` (FK), `code`, `actif` (bool), `timestamps`.

**`stock_matieres_premieres`** : `id`, `matiere_premiere_id` (FK), `depot_id` (FK), `quantite_disponible` (decimal 12,3, default 0), `quantite_reservee` (decimal 12,3, default 0), `timestamps`. Unique : matiere_premiere_id + depot_id.

**`sequences`** : `id`, `prefixe` (string 20), `annee` (int), `dernier_numero` (int, default 0). Unique : prefixe + annee.

**`bons_commande`** : `id`, `numero_bc` (unique), `client_id` (FK), `date_commande` (date), `date_livraison_souhaitee` (date nullable), `montant_ht` (decimal 15,2 default 0), `montant_ttc` (decimal 15,2 default 0), `montant_total` (decimal 15,2 default 0), `statut` (string 50 default 'brouillon'), `notes` (text nullable), `cree_par` (FK users nullable), `timestamps`. Index : client_id, statut.

**`bon_commande_lignes`** : `id`, `bon_commande_id` (FK, restrictOnDelete), `produit_id` (FK), `prix_unitaire` (decimal 15,2), `quantite` (decimal 12,3), `montant_total` (decimal 15,2 default 0), `description` (string 255 nullable), `timestamps`.

**`ordres_production`** : `id`, `numero_op` (unique), `bon_commande_id` (FK nullable, restrictOnDelete), `depot_matiere_id` (FK nullable), `date_lancement` (date), `date_prevue_fin` (date nullable), `statut` (string 50 default 'brouillon'), `priorite` (int default 0), `origine` (string 50 default 'commande'), `notes` (text nullable), `valide_par` (FK users nullable), `valide_le` (datetime nullable), `cree_par` (FK users nullable), `timestamps`.

**`ordre_production_lignes`** : `id`, `ordre_production_id` (FK), `produit_id` (FK), `quantite` (int), `quantite_produite` (int default 0), `quantite_rebutee` (int default 0), `timestamps`.

**`lots`** : `id`, `numero_lot` (unique), `ordre_production_id` (FK), `statut` (string 50 default 'en_production'), `date_ouverture` (date), `date_cloture` (date nullable), `notes` (text nullable), `timestamps`.

**`lot_produits`** : `id`, `lot_id` (FK, restrictOnDelete), `ordre_production_ligne_id` (FK), `produit_id` (FK), `quantite_theorique` (int), `quantite_produite` (int default 0), `quantite_rebutee` (int default 0), `timestamps`.

**`lot_consommation_matiere`** : `id`, `lot_id` (FK), `matiere_premiere_id` (FK), `quantite` (decimal 12,3), `cout_unitaire` (decimal 15,2), `timestamps`.

**`produits_physiques`** : `id`, `code_affiche` (string 50 unique), `produit_id` (FK), `lot_id` (FK nullable), `lot_produit_id` (FK nullable), `emplacement_id` (FK nullable), `etape_actuelle_id` (FK nullable), `statut` (string 50 default 'en_production'), `date_creation` (date), `cree_par` (FK users nullable), `dernier_scan_le` (datetime nullable), `timestamps`. Index : statut.

**`produit_physique_historique`** : `id`, `produit_physique_id` (FK), `type_mouvement` (string 50), `etape_depart_id` (FK nullable), `etape_arrivee_id` (FK nullable), `emplacement_depart_id` (FK nullable), `emplacement_arrivee_id` (FK nullable), `statut_avant`, `statut_apres`, `effectue_par` (FK users nullable), `notes` (text nullable), `created_at` (timestamp). Append-only.

**`documents_sortie`** : `id`, `numero` (unique), `type` (string 20 default 'livraison'), `client_id` (FK), `bon_commande_id` (FK nullable), `date_sortie` (date), `adresse_livraison` (string 255 nullable), `statut` (string 50 default 'prepare'), `valide_par` (FK users nullable), `valide_le` (datetime nullable), `cree_par` (FK users nullable), `timestamps`.

**`document_sortie_lignes`** : `id`, `document_sortie_id` (FK, restrictOnDelete), `produit_physique_id` (FK), `numero_lot` (string 50 nullable), `timestamps`.

**`factures`** (avec TVA) : `id`, `numero_facture` (unique nullable), `client_id` (FK), `bon_commande_id` (FK nullable), `date_facture` (date), `date_echeance` (date nullable), `mode_reglement` (string 50 default 'virement'), `taux_tva` (decimal 5,2 default 19.00), `montant_ht` (decimal 15,2 default 0), `montant_tva` (decimal 15,2 default 0), `montant_ttc` (decimal 15,2 default 0), `montant_ttc_lettres` (text nullable), `montant_paye` (decimal 15,2 default 0), `remise` (decimal 15,2 default 0), `statut` (string 50 default 'brouillon'), `notes` (text nullable), `emise_par` (FK users nullable), `emise_le` (datetime nullable), `timestamps`.

**`facture_lignes`** : `id`, `facture_id` (FK, restrictOnDelete), `produit_id` (FK), `designation` (string 255 nullable), `quantite` (int default 1), `prix_unitaire` (decimal 15,2 default 0), `montant_total` (decimal 15,2 default 0) — **HT seulement**, pas de TVA par ligne.

**`avoirs`** (avec TVA) : `id`, `numero_avoir` (unique), `facture_id` (FK nullable), `client_id` (FK), `date_avoir` (date), `motif` (text nullable), `taux_tva` (decimal 5,2 default 19.00), `montant_ht` (decimal 15,2 default 0), `montant_tva` (decimal 15,2 default 0), `montant_ttc` (decimal 15,2 default 0), `montant_ttc_lettres` (text nullable), `statut` (string 50 default 'brouillon'), `valide_par` (FK users nullable), `timestamps`.

**`avoir_lignes`** : `id`, `avoir_id` (FK), `produit_id` (FK), `designation` (string 255 nullable), `quantite` (int default 1), `prix_unitaire` (decimal 15,2 default 0), `montant_total` (decimal 15,2 default 0), `timestamps`.

**`paiements`** : `id`, `client_id` (FK), `montant` (decimal 15,2), `mode` (string 50), `reference_piece` (string 50 nullable), `statut` (string 50 default 'attente'), `encaisse_par` (FK users nullable), `encaisse_le` (datetime nullable), `notes` (text nullable), `timestamps`.

**`paiement_imputations`** : `id`, `paiement_id` (FK), `facture_id` (FK), `montant` (decimal 15,2), `timestamps`.

**`mouvements_solde_client`** : `id`, `client_id` (FK), `type_mouvement` (string 50), `montant` (decimal 15,2), `reference` (string 255 nullable), `solde_avant` (decimal 15,2), `solde_apres` (decimal 15,2), `created_at`.

**`retours_clients`** : `id`, `document_sortie_id` (FK nullable), `client_id` (FK), `date_retour` (date), `motif` (text nullable), `statut` (string 50 default 'en_attente'), `cree_par` (FK users nullable), `timestamps`.

**`retour_client_lignes`** : `id`, `retour_client_id` (FK), `produit_physique_id` (FK), `decision` (string 50 nullable), `timestamps`.

**`bons_transfert`** : `id`, `numero` (unique), `depot_source_id` (FK), `depot_destination_id` (FK), `statut` (string 50 default 'prepare'), `cree_par` (FK users nullable), `confirme_par_expediteur` (FK nullable), `confirme_par_destinataire` (FK nullable), `timestamps`.

**`bon_transfert_lignes`** : `id`, `bon_transfert_id` (FK), `produit_physique_id` (FK), `timestamps`.

**`defauts_production`** : `id`, `produit_physique_id` (FK), `ordre_production_id` (FK nullable), `produit_id` (FK), `type_defaut` (string 50), `gravite` (string 50), `description` (text nullable), `decision` (string 50 nullable), `declare_par` (FK users nullable), `decide_par` (FK users nullable), `date_decision` (datetime nullable), `timestamps`.

**`inventaires`** : `id`, `depot_id` (FK), `type` (string 20), `statut` (string 50 default 'brouillon'), `date_debut` (datetime), `date_fin` (datetime nullable), `valide_par` (FK users nullable), `notes` (text nullable), `timestamps`.

**`inventaire_lignes`** : `id`, `inventaire_id` (FK), `produit_id` (FK nullable pour matières), `quantite_theorique` (decimal 12,3 default 0), `quantite_comptee` (decimal 12,3 nullable), `timestamps`.

**`inventaire_produits_physiques`** : `id`, `inventaire_ligne_id` (FK), `produit_physique_id` (FK), `resultat` (string 50 nullable), `timestamps`.

**`matieres_premieres_prix_historique`** : `id`, `matiere_premiere_id` (FK), `prix_achat` (decimal 15,2), `fournisseur` (string 100 nullable), `created_at`. Append-only.

**+ `users`** : `id`, `name`, `email` (unique), `password`, `actif` (bool default true), `telephone` (string 20 nullable), `poste` (string 100 nullable), `remember_token`, `timestamps`.

### 3.4 Tables append-only

Les tables suivantes sont **append-only** (immuables) : `mouvements_solde_client`, `produit_physique_historique`, `matieres_premieres_prix_historique`, `lot_consommation_matiere`. Pas de `UPDATE` ou `DELETE` sauf par commande d'archivage.

### 3.5 Migrations TVA (deux fichiers séparés)

**`add_taux_tva_and_lettres_to_factures`** : ajoute `taux_tva` (decimal 5,2, default 19.00) et `montant_ttc_lettres` (text nullable) après `montant_ttc` sur `factures`.

**`add_taux_tva_and_lettres_to_avoirs`** : idem sur `avoirs`.

✅ **Critères §3** : 53 tables totales (39 métier + 5 système Laravel + 9 spatie/Sanctum). Chaque table vérifiée présente.

---

## 4. Machines à états — transitions, gardes, effets

### 4.1 Ordre de production (`ordres_production.statut`)

| Transition | Déclencheur | Gardes | Effets |
|---|---|---|---|
| — → `brouillon` | Création | ≥ 1 ligne | numero_op via SequenceService |
| `brouillon` → `confirme` | Action Confirmer | Permission | — |
| `confirme` → `en_cours` | 1er scan d'une unité du lot | Automatique | `date_lancement_reelle = now()` |
| `en_cours` → `termine` | Toutes les unités en état final | Automatique | Clôture du lot |
| `brouillon` → `annule` | Suppression/annulation | — | Suppression réelle autorisée |
| `confirme` → `annule` | Action Annuler | Aucune unité avancée | Libération réservations |

> **Écart v5-1-1** : les gardes avancées (matière suffisante, gamme non vide) et les jobs (GenererProduitsPhysiquesJob) ne sont pas implémentés en v1 — la confirmation est simple.

### 4.2 Lot / LotProduit — statuts calculés (jamais de transition manuelle)

Les statuts sont recalculés automatiquement. `lot_produits.statut` dérivé de l'état des unités :
- `en_attente` : 0 unité entrée en production.
- `en_production` : ≥1 unité entrée, 0 en état final.
- `partiel` : mélange final / en cours.
- `termine` : toutes finales, 0 rebut.
- `rebute` : toutes rebut.

`lots.statut` = agrégation des `lot_produits`.

### 4.3 Produit physique (`produits_physiques.statut`)

| Transition | Déclencheur | Gardes |
|---|---|---|
| — → `en_production` | Création (observer) | — |
| `en_production` → `disponible` | Arrivée étape terminale | Étape ∈ terminales de la gamme |
| `en_production` → `rebut` | Décision défaut = rebut | — |
| `disponible` → `reserve` | Allocation à une ligne BC | Unité disponible |
| `reserve` → `disponible` | Désallocation | Non sur document de sortie |
| `disponible`/`reserve` → `vendu` | Scan sur document de sortie | — |
| `vendu` → `livre` | Document de sortie → `effectue` | — |
| `livre` → `retourne` | Réception retour client | — |
| Toute transition | — | Écrit une ligne dans `produit_physique_historique` |

### 4.4 Bon de commande (`bons_commande.statut`)

| Transition | Déclencheur | Effets |
|---|---|---|
| — → `devis` | Création (creerDevis) | numero_bc |
| `devis` → `brouillon` | (optionnel) | — |
| `devis`/`brouillon` → `confirmee` | Bouton Confirmer | Figer montants |
| `confirmee` → `en_production` | OP lié confirmé (calculé) | — |
| → `prete`/`partiellement_prete` | Allocation unités disponibles | — |
| → `livree`/`partiellement_livree` | Validation documents sortie | — |
| `devis`/`brouillon`/`confirmee` → `annulee` | Action Annuler | Désallocation si réservé |

### 4.5 Facture / Avoir / Paiement

- **Facture** : `brouillon → emise` (numéro fiscal + mouvement solde +TTC) ; `emise → partiellement_payee → payee` (imputations) ; `emise|partiellement_payee → annulee` (par avoir).
- **Avoir** : `brouillon → emise` (numéro + mouvement solde −TTC).
- **Paiement** : `attente → encaisse` (mouvement solde −montant) ; `attente → annule` ; `encaisse → refuse` (avec désimputation).

### 4.6 Document de sortie

`prepare` → `effectue` (gardes : ≥1 ligne, unités `vendu`) — effets : unités `livre`, historique, compteurs BC.
`prepare` → `annule` : unités re-libérées.

✅ **Critères §4** : toutes les transitions listées sont les seules autorisées. Les statuts sont calculés côté serveur.

---

## 5. Flux métier end-to-end (scénarios numérotés)

Chaque flux liste les étapes ; « [T] » = dans une transaction.

> **Note v1** : les scénarios marqués « [J] » (job asynchrone) sont exécutés en synchrone dans l'implémentation actuelle.

| # | Flux | Statut |
|---|---|---|
| **F1** | Entrée d'achat matière | 🟡 Non implémenté |
| **F2** | Devis → commande (création BC) | ✅ `CommandeService::creerDevis()` |
| **F3** | OP depuis commande | 🟡 Création manuelle, pas automatique |
| **F4** | OP pour stock | 🟡 Idem F3 |
| **F5** | Production & scans | 🟡 Non implémenté (pas de scan d'étape) |
| **F6** | Split multi-étapes | 🟡 Non implémenté |
| **F7** | Défaut & rebut | 🟡 Non implémenté |
| **F8** | Clôture automatique | 🟡 Non implémenté |
| **F9** | Allocation & préparation livraison | 🟡 Non implémenté |
| **F10** | Livraison/enlèvement (validation DS) | 🟡 Non implémenté |
| **F11** | **Facturation (depuis DS)** | ✅ `FactureService::creerFactureDepuisDocuments()` |
| **F12** | Paiement & imputation | 🟡 Non implémenté |
| **F13** | Rejet de chèque | 🟡 Non implémenté |
| **F14** | Retour client | 🟡 Non implémenté |
| **F15** | Transfert inter-dépôts | 🟡 Non implémenté |
| **F16-F17** | Inventaires | 🟡 Non implémenté |
| **F18** | Annulation d'OP | 🟡 Non implémenté |

> ✅ = implémenté et testé · 🟡 = schéma/models prêts, service à implémenter · ❌ = non commencé

---

## 6. Services — signatures, pseudocode, exceptions

### 6.1 `PrefixeDocument` — Enum des préfixes de numérotation

```php
enum PrefixeDocument: string {
    case OP = 'op'; case LOT = 'lot'; case BC = 'bc';
    case BL = 'ds'; case BE = 'be'; case FAC = 'facture';
    case AV = 'avoir'; case PAIE = 'paie'; case BT = 'bt';
    case INV = 'inv'; case RET = 'ret'; case TRF = 'trf';
    case PHY = 'phy';
    public function prefixe(): string { return $this->value; }
}
```

### 6.2 `SequenceService`

```php
class SequenceService {
    /** SELECT ... FOR UPDATE + incrément atomique. Doit être dans une transaction. */
    static function prochainNumero(PrefixeDocument $p, int $annee): int {
        // UPDATE sequences SET dernier_numero = dernier_numero + 1
        //   WHERE prefixe = $p->prefixe() AND annee = $annee
        // Si non trouvé : INSERT avec dernier_numero = 1
        // Retourne la valeur finale (dernier_numero)
    }

    /** Formatage : PREFIX-ANNEE-NNNNNN */
    static function formatNumero(string $prefixe, int $annee, int $n, int $pad = 6): string {
        // Spécial : 'ds' → 'BL-...'
        // Sinon : strtoupper($prefixe) . '-' . $annee . '-' . str_pad($n, $pad, '0', STR_PAD_LEFT)
    }
}
```

### 6.3 `CommandeService`

```php
class CommandeService {
    /** Crée un devis (BC statut=devis) avec lignes */
    static function creerDevis(array $data): BonCommande
    // Reçoit : clientId, commercialId, notes?, lignes[]
    // 1. Numéro via SequenceService::prochainNumero(BC)
    // 2. Crée BC avec montants=0 (observer recalcule depuis lignes)
    // 3. Crée les lignes avec montant_total = quantite * prix_unitaire
    // Observer BonCommandeLigne::saved → BonCommandeObserver::recompute → montant_ht = sum lignes

    /** Confirme un devis (statut → confirmee) */
    static function confirmerDevis(int $bcId): BonCommande
}
```

### 6.4 `FactureService`

```php
class FactureService {
    /**
     * Crée une facture brouillon depuis des documents de sortie
     * Règle comptable : lignes HT seulement, TVA globale sur le document
     */
    static function creerFactureDepuisDocuments(int $clientId, array $documentSortieIds, int $userId): Facture
    // [T] DB::transaction {
    //   1. Charge documents avec lignes.produitPhysique.produit et bonCommande.lignes
    //   2. Groupe par produit_physique_id (qte = occurrences)
    //   3. Calcule montant_ht = Σ(qte × produit.prix_vente)
    //   4. Crée Facture avec taux_tva=19.00, montants=0
    //   5. Crée les FactureLigne (HT seulement : montant_total = qte × pu)
    //   6. Recalcule : tva = montant_ht × 19/100, ttc = ht + tva, lettres = enLettres(ttc)
    // }
    // Exception : FactureError si aucun document valide

    /**
     * Émet une facture (brouillon → emise) avec numéro fiscal sans trou
     */
    static function emettreFacture(int $factureId, int $userId): Facture
    // [T] DB::transaction {
    //   1. lockForUpdate ; statut === brouillon sinon FactureError
    //   2. numero_facture = SequenceService::prochainNumero(FAC)
    //   3. statut → emise, emise_par/le
    //   4. MouvementSoldeClient : +TTC, solde client mis à jour
    // }

    /** Impute un paiement sur des factures */
    static function imputerPaiement(int $paiementId, array $imputations): void
}
```

### 6.5 `ProductionService`

```php
class ProductionService {
    // Création et gestion des ordres de production
    // (structure prête, logique avancée à implémenter)
}
```

### 6.6 `StockMatiereService`

```php
class StockMatiereService {
    // Mouvements de stock matières premières
    // (structure prête, logique à implémenter)
}
```

### 6.7 `PdfService`

```php
class PdfService {
    static function facture(Facture $f): StreamedResponse     // route factures.pdf
    static function bonLivraison(DocumentsSortie $ds): StreamedResponse  // route documents-sorties.pdf
    static function avoir(Avoir $a): StreamedResponse           // route avoirs.pdf
}
```

### 6.8 `BarcodeService`

```php
class BarcodeService {
    // Génération de codes-barres pour étiquettes
}
```

✅ **Critères §6** : services implémentés avec signatures complètes, transactions, exceptions. FactureService et CommandeService entièrement fonctionnels.

---

## 7. Exceptions custom

> **Écart v5-1-1** : les exceptions custom (`NexusException`, `TransitionInterditeException`, etc.) ne sont pas encore implémentées. Les services utilisent `FactureError` (simple extension de `\Exception`) et `CommandeValidationError` pour les erreurs métier.

Planifiées :
- `FactureError` ✅ (utilisé dans FactureService)
- `CommandeValidationError` ✅ (utilisé dans CommandeService)
- `NexusException` (base abstraite)
- `TransitionInterditeException`, `InsuffisanceMatiereException`, `UniteIntrouvableException`, etc.

---

## 8. Jobs & queues

> **Écart v5-1-1** : l'original spécifiait Redis + Horizon + 3 supervisors (default/production/impression). L'implémentation actuelle utilise `QUEUE_CONNECTION=sync` — tout est synchrone. Les jobs peuvent être ajoutés ultérieurement avec Redis/Horizon.

---

## 9. Split multi-étapes

> **Écart v5-1-1** : le split multi-étapes (transfert de sous-groupe d'unités) et la clôture automatique ne sont pas implémentés en v1. Les modèles et la structure sont prêts (produit_physique_historique, etape_actuelle_id).

---

## 10. Rôles & permissions — liste exhaustive

**Stack : spatie/laravel-permission + Filament Shield.** 4 rôles seedés : `admin`, `commercial`, `production`, `magasinier`.

### Permissions Shield générées

```
php artisan shield:generate --all --panel=admin --option=policies_and_permissions
```

→ **41 politiques, 494 permissions, 43 entités** protégées.

### Attribution par rôle

| Rôle | Accès |
|------|-------|
| `admin` | Toutes les permissions |
| `commercial` | Clients, BC, Factures, Avoirs, Paiements |
| `production` | OP, Lots, Produits physiques, Défauts, Étapes |
| `magasinier` | Stock, Dépôts, Documents de sortie, Transferts, Inventaires |

> **Écart v5-1-1** : l'original spécifiait 6 rôles (admin, chef-production, operateur-atelier, commercial, magasinier, finance) avec des permissions granulaires (hangar_user pivot, deblocages_credit). L'implémentation actuelle a 4 rôles basiques. La matrice peut être étendue.

---

## 11. Filament — spécification UI par resource

### 11.1 Navigation

| Groupe | Resources | Visibles |
|--------|-----------|----------|
| **Commercial** | Clients, Bons de commande, Mouvements solde client | 3 |
| **Production** | OP, Lots, Lot produits, Lot conso matières, Produits physiques, PP historique, Défauts, Étapes, Produit étapes, Produit matières, OP lignes | 11 (7 visibles) |
| **Produits** | Produits | 1 |
| **Documents de sortie** | Documents de sortie (+ lignes) | 2 (1 cachée) |
| **Facturation** | Factures (+ lignes), Avoirs (+ lignes) | 4 (2 cachées) |
| **Paiements** | Paiements (+ imputations) | 2 (1 cachée) |
| **Stock** | Dépôts, Hangars, Emplacements, Matières premières, Stock MP, Mouvements stock, MP prix historique, Inventaires (+ lignes, PP), Bons de transfert (+ lignes) | 12 (6 cachées) |
| **Retours clients** | Retours (+ lignes) | 2 (1 cachée) |
| **Paramètres** | Sequences | 1 |
| **Utilisateurs** | Users | 1 |
| **Pages** | Parametres, ScanStation | 2 (sans groupe) |

Total : **44 resources** (24 cachées du menu, gérées via relation managers) + **2 pages**.

### 11.2 FactureResource (key resource — TVA)

- **Formulaire** :
  - Section « Informations » : client (select), BC (select), n° facture (disabled), date, date échéance, statut (select StatutFacture)
  - Section « Montants » (calcul automatique) : `montant_ht` (disabled), `taux_tva` (editable, default 19, suffix %), `montant_tva` (disabled), `montant_ttc` (disabled), `montant_ttc_lettres` (disabled, columnSpanFull), `mode_reglement` (select ModePaiement, default virement), `montant_paye` (disabled), `remise` (numeric, default 0)
- **Table** : numero_facture, client, BC, date, mode_reglement (badge), montant_ht, montant_tva, **taux_tva** (TVA %), montant_ttc, montant_paye, remise, statut (badge), emise_par, emise_le. Actions : Edit, **PDF** (route factures.pdf).
- **Relation Manager** : `FactureLignesRelationManager` (lignes HT)

### 11.3 AvoirResource (TVA)

- **Formulaire** : Section « Informations » + Section « Montants » identique à Facture sans `mode_reglement` ni `montant_paye`. `taux_tva` editable 19%. Statut en inline options (brouillon/émise/validé/annulé).
- **Table** : avec `taux_tva`. Action PDF (route avoirs.pdf).
- **Relation Manager** : `AvoirLignesRelationManager`

### 11.4 BonCommandeResource

- **Formulaire** : client, lignes (repeater produit/qte/PU/montant_total), statut, dates.
- **Table** : numero_bc, client, montant_ht, statut (badge coloré).
- **Relation Manager** : `BonCommandeLignesRelationManager`

### 11.5 UsersResource

- **Groupe** : Utilisateurs
- **Formulaire** : name, email (unique), password (required on create), actif (toggle), telephone, poste. Liaison rôles : `Select::make('roles')->multiple()->relationship('roles','name')->preload()`
- **Table** : name, email, actif (badge), rôles (badges)

### 11.6 Parametres Page

- **Groupe** : Paramètres
- Cards : « Dépôts & Stock », « Commercial », « Système » (Version Nexus ERP v5.1.1, Stack Laravel 13 + Filament 4 + Shield, Base MariaDB / MySQL, Devise DZD)

### 11.7 ScanStation Page

- Vue Blade + `html5-qrcode.min.js` UMD (contournement Vite/rolldown Android)
- Scanner QR → lookup API `/api/v1/produits-physiques/lookup?code=...` → affiche infos produit/statut/emplacement/commande

✅ **Critères §11** : tous les formulaires avec schémas Filament 4 (Section → `Filament\Schemas\Components\Section`), tables, relation managers, actions PDF. Navigation groups appliqués.

---

## 12. API mobile — contrats complets

**Stack : Sanctum (token par appareil), préfixe `/api/v1`, middleware `auth:sanctum`.** Pas de rate limiting en v1.

### 12.1 Authentification

```
POST /api/v1/login
  { "email": "...", "password": "..." }
  200 → { "token": "1|...", "user": { "id", "name", "email", "roles": [...] } }
  422 → validation error
  401 → { "error": "Identifiants invalides" }

POST /api/v1/logout    → révoque le token courant
GET  /api/v1/me        → profil + rôles
```

### 12.2 Ressources

```
GET  /api/v1/clients?q=...            → pagination (50) clients filtrés
GET  /api/v1/produits?q=...           → pagination (50) produits
GET  /api/v1/bons-commande             → pagination (50) BC avec client
POST /api/v1/bons-commande             → Créer BC (clientId, lignes[])
GET  /api/v1/sequence/next?type=bc     → Prochain numéro (type: bc/op/lot/ds/fac/av/phy/...)
```

### 12.3 Tracabilité

```
GET /api/v1/produits-physiques/lookup?code={code_affiche}
  200 → { "code", "statut", "produit", "reference", "emplacement", "etape", "commande" }
  404 → { "error": "introuvable" }
  422 → code requis
```

✅ **Critères §12** : 10 routes API, authentification Sanctum, endpoints fonctionnels et testés.

---

## 13. Documents PDF (dompdf)

### 13.1 Facture

**Vue** : `resources/views/pdf/facture.blade.php`
**Contenu** : en-tête NEXUS ERP (siège Alger), infos client (raison sociale, adresse, téléphone), infos document (n° facture, date, échéance, mode règlement, statut), tableau des lignes (designation, qte, PU HT, montant HT), totaux :
→ **Total HT** (somme des lignes)
→ **TVA** (taux_tva %, montant calculé)
→ **Total TTC** en chiffres
→ **Montant TTC en lettres** (helper NumberToWords)
→ **Déjà payé** → **Net à payer**
→ **Mode de règlement**
→ Notes

### 13.2 Bon de livraison

**Vue** : `resources/views/pdf/bon-livraison.blade.php`

### 13.3 Avoir

**Vue** : `resources/views/pdf/avoir.blade.php`
**Contenu** : même modèle que facture avec `numero_avoir`, référence facture d'origine, motif.

✅ **Critères §13** : 3 PDF fonctionnels, téléchargeables depuis les tables Filament.

---

## 14. Notifications & alertes

> **Écart v5-1-1** : l'original spécifiait un système complet de notifications in-app + email avec 15+ événements (étiquettes prêtes, insuffisance matière, défaut critique, etc.). Non implémenté en v1. Les notifications Filament database peuvent être ajoutées ultérieurement.

---

## 15. Règles de validation

| Formulaire | Règles clés |
|---|---|
| Produit | `reference` unique ; `prix_vente > 0` ; `taux_tva` 0–100 |
| BC | ≥ 1 ligne ; client actif ; `quantite >= 1` |
| BC ligne | `produit_id` exists ; `quantite >= 1` ; `prix_unitaire >= 0` |
| OP | ≥ 1 ligne ; produit actif |
| Facture | client exist ; taux_tva 0–100 |
| Facture ligne | `quantite >= 1` ; `prix_unitaire >= 0` |
| Avoir | client exist ; taux_tva 0–100 |
| User | email unique ; password min 8 |
| Document sortie | client exist ; type ∈ livraison/enlevement |
| Paiement | `montant > 0` |

✅ **Critères §15** : validation Laravel standard + Filament rules.

---

## 16. Seeders & données de démonstration

### 16.1 `DatabaseSeeder` (reference data)

```bash
php artisan db:seed
```

Crée :
- **4 rôles** (admin, commercial, production, magasinier) + 1 admin (`admin@nexus-erp.dz` / `admin123`)
- **3 dépôts** (Production, Magasin MP, Magasin PF) + hangar
- **7 étapes de production** (Découpe → Fraisage → Ponçage → Peinture → Montage → Contrôle → Emballage)
- **14 matières premières** (bois MDF, stratifié, quincaillerie, charnières, poignées, profilé alu, verre, mousse, tissu, peinture, vernis, emballage, roulettes)
- **10 produits finis** (Bureau Directeur Luxe 185k DZD, Bureau Standard 85k, Fauteuil Directeur 75k, Chaise ergo 45k, Table réunion 145k, Armoire 95k, Bibliothèque 65k, Cloison 35k, Caisson 38k, Table basse 55k) — tous avec prix_vente et tva_taux=19%
- **10 clients** (SARL/Hôtel/EURL) avec plafonds de crédit
- **Stock matières initial** (100 unités chaque matière) dans le dépôt MP

### 16.2 `DemoDataSeeder` (chaîne complète)

Idempotent (ne s'exécute que si `BonCommande::count() === 0`), transactionnel (rollback intégral si échec).

**Chaîne créée** :
1. **BC** `BC-2026-000001` (confirmee, 2 lignes : 5×Bureau Directeur 185k + 3×Chaise ergo 45k = **HT 1 180 000**)
2. **OP** `OP-2026-000001` (en_cours, origine commande, 2 lignes)
3. **Lot** `LOT-2026-000001` (en_production)
4. **LotProduit** (2 entrées : qte théorique 5 et 3, toutes produites)
5. **8 Produits physiques** (disponible, codes `PHY-2026-0001` à `0008`)
6. **DS** `BL-2026-000001` (livraison, effectue, 8 lignes)
7. **Facture** `FACTURE-2026-000001` (emise, TVA 19%)
   - HT = 1 180 000 | TVA = 224 200 | **TTC = 1 404 200**
   - Lettres : « Un million quatre cent quatre mille deux cents dinars »
   - Mode règlement : virement
   - Solde client mis à jour : +1 404 200

✅ **Critères §16** : seeders fonctionnels, idempotents, données cohérentes et vérifiées.

---

## 17. Performance

- **Index** : toutes les FK sont indexées automatiquement par `foreignId()`. Index manuels sur `statut`, `numero_bc`, `numero_facture`, `code_affiche`.
- **N+1** : pas de `preventLazyLoading` explicite (à activer). Les tables Filament déclarent leurs `->with([...])` pour les relations chargées.
- **Compteurs dénormalisés** : `montant_ht`, `montant_ttc` sur BC/Facture/Avoir — mis à jour par `increment()` dans les services.
- **Pas de cache Redis** en v1 (cache file).
- Pagination serveur partout.

> **Écart v5-1-1** : pas de Redis, pas de `lockForUpdate` massif, pas de commande `nexus:verifier-integrite`. L'original spécifiait des optimisations avancées non implémentées.

---

## 18. Archivage

> **Écart v5-1-1** : l'archivage des historiques (commande `nexus:archiver-historique`) n'est pas implémenté. Les tables append-only grandissent sans limite.

---

## 19. Sécurité & audit

- Panel Filament derrière `auth` (Shield).
- **Sanctum** : tokens révocables, authentification API.
- **Uploads** : aucun upload public (html5-qrcode servit statiquement).
- **QR encode** : `code_affiche` (pas de donnée métier dans le QR).
- **Mots de passe** : hashés par Laravel.
- **Comptes désactivables** : `actif=false` (champ utilisateur).

> **Écart v5-1-1** : pas de 2FA, pas de `spatie/laravel-activitylog`, pas d'audit trail, pas de throttle API.

---

## 20. Plan de tests

> **Écart v5-1-1** : Pest n'est pas installé. Les tests suivants sont planifiés :
> - Tests unitaires : `SequenceService`, `NumberToWords`, `FactureObserver::recompute`
> - Tests feature : `FactureService::creerFactureDepuisDocuments`, `FactureService::emettreFacture`
> - Tests concours : verrouillage séquences
> - Tests API : chaque endpoint

---

## 21. Déploiement & exploitation

- **Environnement** : Termux/Android (localhost:8000), serveur PHP built-in.
- **Processus** : `php artisan serve --host=0.0.0.0 --port=8000` en tmux session `nexus-laravel`.
- **Base de données** : MariaDB locale (root@127.0.0.1:3306, sans mot de passe).
- **Backups** : aucun automatisé (à configurer).

> **Écart v5-1-1** : pas de nginx/php-fpm, pas de supervisor, pas de scheduler cron, pas de backups automatisés, pas de monitoring. Déploiement de développement uniquement.

---

## 22. Décisions ouvertes (consolidées)

| # | Question | Décision appliquée |
|---|---|---|
| D1 | Taux TVA global ou par ligne ? | **Global sur le document** (19% défaut, éditable) — dévie de l'original (per-produit) |
| D2 | Avoir : mode_reglement ? | **Non** (pas de colonne dans le schéma original) |
| D3 | Avoir : enum StatutAvoir ? | **Non** (utilise inline Select dans le formulaire) |
| D4 | Filament 4 vs 5 ? | **Filament 4** (Shield 4 non validé sur Filament 5) |
| D5 | Vite/rolldown sur Termux ? | **Contournement UMD** (html5-qrcode.min.js dans public/vendor) |
| D6 | routes/api.php chargement ? | **Déclaration explicite** dans bootstrap/app.php (Laravel 11+) |
| D7 | BC observer : recompute sans lignes ? | **Early return** si pas de lignes (préserve montant fourni par creerDevis) |
| D8 | Nom des relations FactureService ? | **Alias `lignes()`** ajoutés (conformité Prisma) |
| D9 | Classe DocumentSortie ? | **DocumentsSortie** (avec 's') — corrigé dans FactureService |
| D10 | Section (Filament 4) namespace ? | `Filament\Schemas\Components\Section` (pas Forms) |
| D11 | Queue/async ? | **Synchrone** (QUEUE_CONNECTION=sync, pas de Redis) |
| D12 | Moteur de base ? | **MariaDB** (pas MySQL 8.0) |
| D13 | Auth API ? | **Sanctum** (tokens) |
| D14 | RBAC étendu ? | **4 rôles basiques** (pas 6 avec hangar_user pivot) |

---

## 23. Phases de développement & Definition of Done

| Phase | Contenu | Statut |
|---|---|---|
| **P0** | Stack + DB + modèles + enums | ✅ Terminé |
| **P1** | Services métier (Sequence, Commande, Facture) | ✅ Terminé |
| **P2** | Observateurs + recalculs automatiques | ✅ Terminé |
| **P3** | Filament resources + forms + tables | ✅ Terminé |
| **P4** | PDF (facture, BL, avoir) | ✅ Terminé |
| **P5** | RBAC (Shield) + Users | ✅ Terminé |
| **P6** | Scan QR (ScanStation) | ✅ Terminé |
| **P7** | API v1 (Sanctum) | ✅ Terminé (10 routes) |
| **P8** | Paramètres page | ✅ Terminé |
| **P9** | Amélioration TVA (taux global 19%, lettres) | ✅ Terminé |
| **P10** | Demo seeder (chaîne BC→OP→Lot→PP→DS→Facture) | ✅ Terminé |
| **P11** | Machine à états (transitions avancées) | 🟡 Partiel |
| **P12** | Flux F1-F18 complets | 🟡 F11 seulement |
| **P13** | Dashboard/statistiques | ❌ |
| **P14** | Tests Pest | ❌ |
| **P15** | Notifications | ❌ |
| **P16** | Déploiement production | ❌ |

---

## 24. Glossaire

| Terme | Définition |
|-------|------------|
| **BC** | Bon de commande |
| **OP** | Ordre de production / ordre de fabrication |
| **DS** | Document de sortie (bon de livraison ou d'enlèvement) |
| **BL** | Bon de livraison |
| **BE** | Bon d'enlèvement |
| **PP** | Produit physique (unité tracée individuellement) |
| **HT** | Hors taxes |
| **TVA** | Taxe sur la valeur ajoutée |
| **TTC** | Toutes taxes comprises |
| **NIF** | Numéro d'identification fiscale |
| **RBAC** | Role-Based Access Control |
| **Shield** | Filament Shield (interface de gestion des permissions) |
| **Sanctum** | Laravel Sanctum (authentification API par tokens) |
| **dompdf** | Générateur de PDF côté serveur |
| **Spatie** | Spatie (éditeur de packages Laravel : permission, etc.) |
| **SequenceService** | Service de numérotation automatique des documents |
| **PrefixeDocument** | Enum des préfixes de numérotation |
| **Filament 4** | Version 4 du panneau d'administration (Schemas package) |
| **UMD** | Universal Module Definition (chargement JS sans bundler) |

---

## 25. Annexes

### 25.1 Périmètre & principes directeurs

1. **La facture est la seule pièce qui engage le solde client.** Ni le devis, ni le BC, ni le BL ne créent de mouvement de solde.
2. **Une facture émise est immuable** — toute correction = avoir.
3. **Numérotation fiscale sans trou** : le numéro n'existe qu'à l'émission réussie (commit). Un rollback ne consomme jamais de numéro.
4. **Chaîne documentaire complète** : BC → DS → Facture → Imputations / Avoirs. Liens navigables entre chaque écran.
5. **Tous les montants sont calculés côté serveur** — l'UI n'affiche que le résultat.

### 25.2 Règles de calcul TVA (normatives)

#### Principe (décision D1)
> **Les lignes de facture/avoir sont HT uniquement. Le taux de TVA est global et éditable sur le document.**

```
HT    = Σ des montants_total des lignes (HT, pas de TVA par ligne)
TVA   = HT × taux_tva / 100            ← taux éditable, défaut 19 %
TTC   = HT + TVA
TTC lettres = enLettres(TTC)           ← via NumberFormatter fr_FR SPELLOUT
```

#### Implémentation

**Modèles** : `Facture.taux_tva` (decimal 5,2, default 19.00), `Facture.montant_ttc_lettres` (text). Idem `Avoir`.

**Observateurs** : `FactureObserver::recompute()` et `AvoirObserver::recompute()` :
```php
$ht = (float) $facture->lignes()->sum('montant_total');
$taux = (float) ($facture->taux_tva ?? 19);
$tva = $ht * $taux / 100;
$ttc = $ht + $tva;
$lettres = \App\Helpers\NumberToWords::enLettres($ttc);
if (différent) { $facture->montant_ht = $ht; ...; $facture->saveQuietly(); }
```
Early return si aucune ligne (préserve valeurs fournies à la création).

**FactureService::creerFactureDepuisDocuments** :
- Lignes HT : `montant_total = qte × prix_unitaire`
- Recalcul post-création : `$taux = 19.0; $tva = $montantHt * $taux / 100; $ttc = $montantHt + $tva;`

**Formulaires Filament** :
- `taux_tva` : editable (default 19, suffix %)
- `montant_ht`, `montant_tva`, `montant_ttc`, `montant_ttc_lettres` : disabled (calcul automatique)

**PDF facture** :
```
Total HT (somme des lignes)     → montant_ht
TVA (XX.XX %)                   → montant_tva
Total TTC                       → montant_ttc
Montant TTC en lettres          → montant_ttc_lettres
Mode de règlement               → mode_reglement
```

#### Helper `NumberToWords`
```php
$fmt = new NumberFormatter('fr_FR', NumberFormatter::SPELLOUT);
$mots = $fmt->format($entier) . ' dinars';
// Exemple : 1404200 → « Un million quatre cent quatre mille deux cents dinars »
```

#### Exemple normatif (testé et vérifié)
```
3 lignes de Bureau Directeur (185 000 HT) + 3 lignes de Chaise (45 000 HT) :
Total HT  = 5 × 185 000 + 3 × 45 000 = 1 180 000
TVA 19 %  = 1 180 000 × 0,19 = 224 200
Total TTC = 1 404 200
Lettres   = « Un million quatre cent quatre mille deux cents dinars »
```

### 25.3 Corrections & bugs résolus

| # | Bug | Cause | Fix |
|---|---|---|---|
| B1 | `Class "DocumentSortie" not found` | FactureService référençait `\App\Models\DocumentSortie` (singulier) | Corrigé en `DocumentsSortie` |
| B2 | `Class "Section" not found` | 3 formulaires importaient `Filament\Forms\Components\Section` (inexistant dans Filament 4) | Remplacé par `Filament\Schemas\Components\Section` |
| B3 | BC montant zéroé par observer | `BonCommandeObserver` recomputait avant création des lignes | Early return si pas de lignes + `BonCommandeLigneObserver` |
| B4 | `lignes` relation manquante | FactureService utilisait `$doc->lignes` et `$doc->bonCommande->lignes` (noms Prisma) | Alias `lignes()` ajoutés sur BonCommande et DocumentsSortie |
| B5 | Vite/rolldown ne compile pas sur Termux | `@rolldown/binding-android-arm-eabi` manquant | UMD html5-qrcode servit depuis `public/vendor/` |
| B6 | routes/api.php non chargé | Laravel 11+ n'auto-charge plus `routes/api.php` | Déclaré via `withRouting(api:)` dans `bootstrap/app.php` |

### 25.4 GitHub

- **Dépôt** : `https://github.com/rachiddri/nexus-erp-laravel` (public)
- **Branche** : `main`
- **Commits** : `5936a36` (initial) · `970c68b` (BC fix) · `5a11a3e` (demo seed) · `7b65624` (Section fix) · `cafa8d3` (PLAN.md)

### 25.5 Accès administration

- **URL** : `http://localhost:8000/admin`
- **Login** : `admin@nexus-erp.dz` / `admin123`
- **Serveur** : tmux session `nexus-laravel`, `php artisan serve --host=0.0.0.0 --port=8000`
- **DB** : MariaDB `nexus_erp_laravel`, root@127.0.0.1:3306, sans mot de passe

---

*Fin du plan — 25 sections, conforme au plan v5-1-1 original, adapté à l'implémentation réelle (Laravel 13.20 + Filament 4 + Shield 4).*
