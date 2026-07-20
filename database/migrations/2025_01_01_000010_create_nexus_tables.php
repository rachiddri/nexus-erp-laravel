<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Nexus ERP — schéma complet (39 tables métier).
     * Conversion fidèle du schema.prisma (Next.js/Prisma) vers Laravel/MariaDB.
     * Les colonnes sont en snake_case (convention Laravel). La RBAC utilise
     * spatie/laravel-permission (via Filament Shield) et non les tables
     * custom roles/permissions/user_roles/role_permissions du schéma Prisma.
     */
    public function up(): void
    {
        Schema::create('sequences', function (Blueprint $table) {
            $table->id();
            $table->string('prefixe', 20);
            $table->integer('annee')->default(2025);
            $table->integer('dernier_numero')->default(0);
            $table->unique(['prefixe', 'annee']);
            $table->timestamps();
        });

        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('raison_sociale', 200);
            $table->string('nif', 30)->unique()->nullable();
            $table->string('email', 150)->nullable();
            $table->string('tel', 30)->nullable();
            $table->text('adresse')->nullable();
            $table->decimal('plafond_credit', 15, 2)->default(0);
            $table->decimal('solde', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        Schema::create('depots', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('code', 20)->unique();
            $table->string('type', 20)->default('produit_fini');
            $table->string('adresse', 255)->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        Schema::create('hangars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('depot_id');
            $table->string('nom', 100);
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
            $table->unique(['nom', 'depot_id']);
            $table->foreign('depot_id')->references('id')->on('depots')->onDelete('restrict');
            $table->foreign('responsable_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('emplacements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hangar_id');
            $table->string('code_emplacement', 100);
            $table->string('emplacement_able_type', 100)->nullable();
            $table->unsignedBigInteger('emplacement_able_id')->nullable();
            $table->string('zone', 50)->nullable();
            $table->integer('capacite_max')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
            $table->unique(['emplacement_able_type', 'emplacement_able_id', 'code_emplacement'], 'emp_able_code_unique');
            $table->foreign('hangar_id')->references('id')->on('hangars')->onDelete('restrict');
        });

        Schema::create('etapes_production', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 255)->unique();
            $table->integer('ordre')->default(0);
            $table->text('description')->nullable();
            $table->string('type_controle', 50)->nullable();
            $table->decimal('seuil_conformite', 5, 2)->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        Schema::create('matieres_premieres', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 255);
            $table->string('code', 50)->unique();
            $table->text('description')->nullable();
            $table->string('unite', 20)->default('unite');
            $table->decimal('cout_unitaire_moyen', 15, 2)->default(0);
            $table->decimal('cout_unitaire_actuel', 15, 2)->nullable();
            $table->decimal('stock_alerte_min', 15, 2)->nullable()->default(0);
            $table->text('fiche_technique')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        Schema::create('matieres_premieres_prix_historique', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('matiere_premiere_id');
            $table->decimal('prix_avant', 15, 2);
            $table->decimal('prix_apres', 15, 2);
            $table->string('motif', 255)->nullable();
            $table->dateTime('date_debut')->default(now());
            $table->dateTime('date_fin')->nullable();
            $table->unsignedBigInteger('utilisateur_id')->nullable();
            $table->timestamps();
            $table->foreign('matiere_premiere_id')->references('id')->on('matieres_premieres')->onDelete('restrict');
            $table->foreign('utilisateur_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('stock_matieres_premieres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('matiere_premiere_id');
            $table->unsignedBigInteger('depot_id');
            $table->decimal('quantite_disponible', 15, 2)->default(0);
            $table->decimal('quantite_reservee', 15, 2)->default(0);
            $table->timestamps();
            $table->unique(['matiere_premiere_id', 'depot_id']);
            $table->foreign('matiere_premiere_id')->references('id')->on('matieres_premieres')->onDelete('restrict');
            $table->foreign('depot_id')->references('id')->on('depots')->onDelete('restrict');
        });

        Schema::create('mouvements_stock_matiere', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('matiere_premiere_id');
            $table->unsignedBigInteger('depot_id');
            $table->string('type_mouvement', 50);
            $table->decimal('quantite', 15, 2);
            $table->decimal('cout_unitaire', 15, 2)->default(0);
            $table->string('reference', 255)->nullable();
            $table->string('document_lie', 255)->nullable();
            $table->unsignedBigInteger('cree_par')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('matiere_premiere_id')->references('id')->on('matieres_premieres')->onDelete('restrict');
            $table->foreign('depot_id')->references('id')->on('depots')->onDelete('restrict');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 255);
            $table->string('reference', 100)->unique();
            $table->text('description')->nullable();
            $table->string('categorie', 100)->nullable();
            $table->decimal('prix_vente', 15, 2)->default(0);
            $table->decimal('tva_taux', 5, 2)->default(19.00);
            $table->integer('stock_alerte_min')->default(0);
            $table->string('gamme', 255)->nullable();
            $table->decimal('longueur', 10, 2)->nullable();
            $table->decimal('largeur', 10, 2)->nullable();
            $table->decimal('hauteur', 10, 2)->nullable();
            $table->decimal('poids', 10, 2)->nullable();
            $table->string('image_principale', 255)->nullable();
            $table->text('fiches_techniques')->nullable();
            $table->text('notes_production')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        Schema::create('produit_etapes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produit_id');
            $table->unsignedBigInteger('etape_production_id');
            $table->integer('ordre')->default(0);
            $table->integer('duree_minutes')->nullable();
            $table->text('instructions')->nullable();
            $table->timestamps();
            $table->unique(['produit_id', 'etape_production_id']);
            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('restrict');
            $table->foreign('etape_production_id')->references('id')->on('etapes_production')->onDelete('restrict');
        });

        Schema::create('produit_matiere_premiere', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produit_id');
            $table->unsignedBigInteger('matiere_premiere_id');
            $table->decimal('quantite', 15, 2);
            $table->decimal('rebut', 15, 2)->nullable()->default(0);
            $table->timestamps();
            $table->unique(['produit_id', 'matiere_premiere_id']);
            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('restrict');
            $table->foreign('matiere_premiere_id')->references('id')->on('matieres_premieres')->onDelete('restrict');
        });

        Schema::create('bons_commande', function (Blueprint $table) {
            $table->id();
            $table->string('numero_bc', 50)->unique();
            $table->unsignedBigInteger('client_id');
            $table->date('date_commande');
            $table->date('date_livraison_souhaitee')->nullable();
            $table->text('adresse_livraison')->nullable();
            $table->string('statut', 50)->default('brouillon');
            $table->decimal('montant_total', 15, 2)->default(0);
            $table->decimal('montant_ht', 15, 2)->default(0);
            $table->decimal('montant_ttc', 15, 2)->default(0);
            $table->decimal('remise_globale', 15, 2)->nullable()->default(0);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('approuve_prix_plancher_par')->nullable();
            $table->dateTime('approuve_prix_plancher_le')->nullable();
            $table->unsignedBigInteger('cree_par')->nullable();
            $table->timestamps();
            $table->index('client_id');
            $table->index('statut');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('restrict');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approuve_prix_plancher_par')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('bon_commande_lignes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bon_commande_id');
            $table->unsignedBigInteger('produit_id');
            $table->integer('quantite')->default(1);
            $table->decimal('prix_unitaire', 15, 2)->default(0);
            $table->decimal('montant_total', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index('bon_commande_id');
            $table->index('produit_id');
            $table->foreign('bon_commande_id')->references('id')->on('bons_commande')->onDelete('restrict');
            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('restrict');
        });

        Schema::create('ordres_production', function (Blueprint $table) {
            $table->id();
            $table->string('numero_op', 50)->unique();
            $table->unsignedBigInteger('bon_commande_id')->nullable();
            $table->unsignedBigInteger('depot_matiere_id')->nullable();
            $table->date('date_lancement')->nullable();
            $table->date('date_prevue_fin')->nullable();
            $table->string('statut', 50)->default('brouillon');
            $table->string('priorite', 20)->nullable()->default('normale');
            $table->string('origine', 50)->default('stock');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('valide_par')->nullable();
            $table->dateTime('valide_le')->nullable();
            $table->unsignedBigInteger('cree_par')->nullable();
            $table->timestamps();
            $table->index('statut');
            $table->foreign('bon_commande_id')->references('id')->on('bons_commande')->onDelete('restrict');
            $table->foreign('depot_matiere_id')->references('id')->on('depots')->onDelete('restrict');
            $table->foreign('valide_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('ordre_production_lignes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ordre_production_id');
            $table->unsignedBigInteger('produit_id');
            $table->integer('quantite')->default(1);
            $table->integer('quantite_produite')->nullable()->default(0);
            $table->integer('quantite_rebutee')->nullable()->default(0);
            $table->timestamps();
            $table->index('ordre_production_id');
            $table->index('produit_id');
            $table->foreign('ordre_production_id')->references('id')->on('ordres_production')->onDelete('restrict');
            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('restrict');
        });

        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->string('numero_lot', 50)->unique();
            $table->unsignedBigInteger('ordre_production_id');
            $table->string('statut', 50)->default('en_cours');
            $table->dateTime('date_ouverture')->default(now());
            $table->dateTime('date_cloture')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('ordre_production_id');
            $table->foreign('ordre_production_id')->references('id')->on('ordres_production')->onDelete('restrict');
        });

        Schema::create('lot_produits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lot_id');
            $table->unsignedBigInteger('ordre_production_ligne_id');
            $table->unsignedBigInteger('produit_id');
            $table->integer('quantite_theorique')->default(0);
            $table->integer('quantite_produite')->default(0);
            $table->integer('quantite_rebutee')->default(0);
            $table->timestamps();
            $table->index('lot_id');
            $table->index('ordre_production_ligne_id');
            $table->index('produit_id');
            $table->foreign('lot_id')->references('id')->on('lots')->onDelete('restrict');
            $table->foreign('ordre_production_ligne_id')->references('id')->on('ordre_production_lignes')->onDelete('restrict');
            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('restrict');
        });

        Schema::create('lot_consommation_matiere', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lot_produit_id');
            $table->unsignedBigInteger('matiere_premiere_id');
            $table->decimal('quantite_consommee', 15, 2)->default(0);
            $table->decimal('quantite_rebutee', 15, 2)->nullable()->default(0);
            $table->timestamps();
            $table->index('lot_produit_id');
            $table->index('matiere_premiere_id');
            $table->foreign('lot_produit_id')->references('id')->on('lot_produits')->onDelete('restrict');
            $table->foreign('matiere_premiere_id')->references('id')->on('matieres_premieres')->onDelete('restrict');
        });

        Schema::create('produits_physiques', function (Blueprint $table) {
            $table->id();
            $table->string('code_affiche', 100)->unique();
            $table->unsignedBigInteger('produit_id');
            $table->unsignedBigInteger('lot_id');
            $table->unsignedBigInteger('lot_produit_id')->nullable();
            $table->unsignedBigInteger('etape_actuelle_id')->nullable();
            $table->unsignedBigInteger('emplacement_id')->nullable();
            $table->string('statut', 50)->default('en_production');
            $table->dateTime('date_creation')->default(now());
            $table->dateTime('date_sortie')->nullable();
            $table->unsignedBigInteger('cree_par')->nullable();
            $table->timestamps();
            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('restrict');
            $table->foreign('lot_id')->references('id')->on('lots')->onDelete('restrict');
            $table->foreign('lot_produit_id')->references('id')->on('lot_produits')->onDelete('restrict');
            $table->foreign('etape_actuelle_id')->references('id')->on('etapes_production')->onDelete('restrict');
            $table->foreign('emplacement_id')->references('id')->on('emplacements')->onDelete('restrict');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('produit_physique_historique', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produit_physique_id');
            $table->string('type_mouvement', 50);
            $table->string('etape_origine', 255)->nullable();
            $table->string('etape_destination', 255)->nullable();
            $table->string('emplacement_origine', 255)->nullable();
            $table->string('emplacement_destination', 255)->nullable();
            $table->unsignedBigInteger('utilisateur_id')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('date_mouvement')->default(now());
            $table->timestamps();
            $table->foreign('produit_physique_id')->references('id')->on('produits_physiques')->onDelete('restrict');
            $table->foreign('utilisateur_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('documents_sortie', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 50)->unique();
            $table->string('type', 20)->default('livraison');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('bon_commande_id');
            $table->date('date_sortie');
            $table->text('adresse_livraison')->nullable();
            $table->string('statut', 50)->default('brouillon');
            $table->unsignedBigInteger('valide_par')->nullable();
            $table->dateTime('valide_le')->nullable();
            $table->unsignedBigInteger('cree_par')->nullable();
            $table->timestamps();
            $table->index('client_id');
            $table->index('bon_commande_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('restrict');
            $table->foreign('bon_commande_id')->references('id')->on('bons_commande')->onDelete('restrict');
            $table->foreign('valide_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('document_sortie_lignes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_sortie_id');
            $table->unsignedBigInteger('produit_physique_id');
            $table->string('numero_lot', 100)->nullable();
            $table->timestamps();
            $table->index('document_sortie_id');
            $table->index('produit_physique_id');
            $table->foreign('document_sortie_id')->references('id')->on('documents_sortie')->onDelete('cascade');
            $table->foreign('produit_physique_id')->references('id')->on('produits_physiques')->onDelete('restrict');
        });

        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->string('numero_facture', 50)->unique()->nullable();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('bon_commande_id')->nullable();
            $table->date('date_facture');
            $table->date('date_echeance')->nullable();
            $table->string('mode_reglement', 50)->nullable()->default('virement');
            $table->decimal('montant_ht', 15, 2)->default(0);
            $table->decimal('montant_tva', 15, 2)->default(0);
            $table->decimal('montant_ttc', 15, 2)->default(0);
            $table->decimal('montant_paye', 15, 2)->default(0);
            $table->decimal('remise', 15, 2)->nullable()->default(0);
            $table->string('statut', 50)->default('brouillon');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('emise_par')->nullable();
            $table->dateTime('emise_le')->nullable();
            $table->timestamps();
            $table->index('client_id');
            $table->index('statut');
            $table->index('bon_commande_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('restrict');
            $table->foreign('bon_commande_id')->references('id')->on('bons_commande')->onDelete('restrict');
            $table->foreign('emise_par')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('facture_lignes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('facture_id');
            $table->unsignedBigInteger('produit_id');
            $table->string('designation', 255)->nullable();
            $table->integer('quantite')->default(1);
            $table->decimal('prix_unitaire', 15, 2)->default(0);
            $table->decimal('montant_total', 15, 2)->default(0);
            $table->timestamps();
            $table->index('facture_id');
            $table->index('produit_id');
            $table->foreign('facture_id')->references('id')->on('factures')->onDelete('restrict');
            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('restrict');
        });

        Schema::create('avoirs', function (Blueprint $table) {
            $table->id();
            $table->string('numero_avoir', 50)->unique();
            $table->unsignedBigInteger('facture_id')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->date('date_avoir');
            $table->text('motif')->nullable();
            $table->decimal('montant_ht', 15, 2)->default(0);
            $table->decimal('montant_tva', 15, 2)->default(0);
            $table->decimal('montant_ttc', 15, 2)->default(0);
            $table->string('statut', 50)->default('brouillon');
            $table->unsignedBigInteger('valide_par')->nullable();
            $table->timestamps();
            $table->foreign('facture_id')->references('id')->on('factures')->onDelete('restrict');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('restrict');
            $table->foreign('valide_par')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('avoir_lignes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('avoir_id');
            $table->unsignedBigInteger('produit_id');
            $table->string('designation', 255)->nullable();
            $table->integer('quantite')->default(1);
            $table->decimal('prix_unitaire', 15, 2)->default(0);
            $table->decimal('montant_total', 15, 2)->default(0);
            $table->timestamps();
            $table->index('avoir_id');
            $table->index('produit_id');
            $table->foreign('avoir_id')->references('id')->on('avoirs')->onDelete('restrict');
            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('restrict');
        });

        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 50)->unique();
            $table->unsignedBigInteger('client_id');
            $table->decimal('montant', 15, 2);
            $table->string('mode_paiement', 50);
            $table->string('statut', 50)->default('en_attente');
            $table->date('date_paiement');
            $table->string('reference_piece', 255)->nullable();
            $table->text('motif_rejet')->nullable();
            $table->unsignedBigInteger('saisi_par')->nullable();
            $table->unsignedBigInteger('encaisse_par')->nullable();
            $table->dateTime('encaisse_le')->nullable();
            $table->timestamps();
            $table->index('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('restrict');
            $table->foreign('saisi_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('encaisse_par')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('paiement_imputations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paiement_id');
            $table->unsignedBigInteger('facture_id');
            $table->decimal('montant', 15, 2);
            $table->timestamps();
            $table->index('paiement_id');
            $table->index('facture_id');
            $table->foreign('paiement_id')->references('id')->on('paiements')->onDelete('restrict');
            $table->foreign('facture_id')->references('id')->on('factures')->onDelete('restrict');
        });

        Schema::create('mouvements_solde_client', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('type_mouvement', 50);
            $table->decimal('montant', 15, 2);
            $table->decimal('solde_avant', 15, 2);
            $table->decimal('solde_apres', 15, 2);
            $table->string('reference', 255)->nullable();
            $table->timestamps();
            $table->index('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('restrict');
        });

        Schema::create('retours_clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->date('date_retour');
            $table->text('motif_global')->nullable();
            $table->string('decision', 50)->nullable();
            $table->string('statut', 50)->default('ouvert');
            $table->unsignedBigInteger('document_sortie_id')->nullable();
            $table->text('motif_rejet')->nullable();
            $table->unsignedBigInteger('cree_par')->nullable();
            $table->unsignedBigInteger('traite_par')->nullable();
            $table->dateTime('traite_le')->nullable();
            $table->timestamps();
            $table->index('client_id');
            $table->index('document_sortie_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('restrict');
            $table->foreign('document_sortie_id')->references('id')->on('documents_sortie')->onDelete('restrict');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('traite_par')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('retour_client_lignes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('retour_client_id');
            $table->unsignedBigInteger('produit_physique_id');
            $table->unsignedBigInteger('produit_id');
            $table->integer('quantite')->default(1);
            $table->text('motif')->nullable();
            $table->string('etat_produit', 50)->default('defectueux');
            $table->timestamps();
            $table->index('retour_client_id');
            $table->index('produit_physique_id');
            $table->index('produit_id');
            $table->foreign('retour_client_id')->references('id')->on('retours_clients')->onDelete('restrict');
            $table->foreign('produit_physique_id')->references('id')->on('produits_physiques')->onDelete('restrict');
            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('restrict');
        });

        Schema::create('bons_transfert', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 50)->unique();
            $table->unsignedBigInteger('depot_origine_id');
            $table->unsignedBigInteger('depot_destination_id');
            $table->date('date_transfert');
            $table->string('motif', 255)->nullable()->default('transfert_stock');
            $table->string('statut', 50)->default('brouillon');
            $table->unsignedBigInteger('cree_par')->nullable();
            $table->unsignedBigInteger('valide_par')->nullable();
            $table->dateTime('valide_le')->nullable();
            $table->timestamps();
            $table->index('depot_origine_id');
            $table->index('depot_destination_id');
            $table->foreign('depot_origine_id')->references('id')->on('depots')->onDelete('restrict');
            $table->foreign('depot_destination_id')->references('id')->on('depots')->onDelete('restrict');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('valide_par')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('bon_transfert_lignes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bon_transfert_id');
            $table->unsignedBigInteger('produit_physique_id');
            $table->string('code_affiche', 100)->nullable();
            $table->timestamps();
            $table->index('bon_transfert_id');
            $table->index('produit_physique_id');
            $table->foreign('bon_transfert_id')->references('id')->on('bons_transfert')->onDelete('cascade');
            $table->foreign('produit_physique_id')->references('id')->on('produits_physiques')->onDelete('restrict');
        });

        Schema::create('inventaires', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 50)->unique();
            $table->string('type', 50);
            $table->unsignedBigInteger('depot_id');
            $table->date('date_inventaire');
            $table->string('statut', 50)->default('brouillon');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('cree_par')->nullable();
            $table->unsignedBigInteger('valide_par')->nullable();
            $table->dateTime('date_validation')->nullable();
            $table->timestamps();
            $table->index('depot_id');
            $table->foreign('depot_id')->references('id')->on('depots')->onDelete('restrict');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('valide_par')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('inventaire_lignes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventaire_id');
            $table->unsignedBigInteger('matiere_premiere_id');
            $table->decimal('quantite_theorique', 15, 2)->default(0);
            $table->decimal('quantite_reelle', 15, 2)->default(0);
            $table->decimal('ecart', 15, 2)->default(0);
            $table->timestamps();
            $table->index('inventaire_id');
            $table->index('matiere_premiere_id');
            $table->foreign('inventaire_id')->references('id')->on('inventaires')->onDelete('cascade');
            $table->foreign('matiere_premiere_id')->references('id')->on('matieres_premieres')->onDelete('restrict');
        });

        Schema::create('inventaire_produits_physiques', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventaire_id');
            $table->unsignedBigInteger('produit_physique_id');
            $table->string('statut', 50)->default('present');
            $table->timestamps();
            $table->unique(['inventaire_id', 'produit_physique_id'], 'inv_pp_unique');
            $table->index('produit_physique_id');
            $table->foreign('inventaire_id')->references('id')->on('inventaires')->onDelete('cascade');
            $table->foreign('produit_physique_id')->references('id')->on('produits_physiques')->onDelete('restrict');
        });

        Schema::create('defauts_production', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lot_produit_id');
            $table->unsignedBigInteger('etape_production_id');
            $table->string('type_defaut', 255);
            $table->text('description')->nullable();
            $table->string('gravite', 50)->default('mineur');
            $table->integer('quantite_impactee')->default(1);
            $table->text('cause_racine')->nullable();
            $table->text('action_immediate')->nullable();
            $table->string('decision', 50)->nullable();
            $table->string('statut', 50)->default('ouvert');
            $table->unsignedBigInteger('signale_par')->nullable();
            $table->unsignedBigInteger('resolu_par')->nullable();
            $table->dateTime('date_resolution')->nullable();
            $table->timestamps();
            $table->index('lot_produit_id');
            $table->index('etape_production_id');
            $table->foreign('lot_produit_id')->references('id')->on('lot_produits')->onDelete('restrict');
            $table->foreign('etape_production_id')->references('id')->on('etapes_production')->onDelete('restrict');
            $table->foreign('signale_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('resolu_par')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        $tables = [
            'defauts_production',
            'inventaire_produits_physiques',
            'inventaire_lignes',
            'inventaires',
            'bon_transfert_lignes',
            'bons_transfert',
            'retour_client_lignes',
            'retours_clients',
            'mouvements_solde_client',
            'paiement_imputations',
            'paiements',
            'avoir_lignes',
            'avoirs',
            'facture_lignes',
            'factures',
            'document_sortie_lignes',
            'documents_sortie',
            'produit_physique_historique',
            'produits_physiques',
            'lot_consommation_matiere',
            'lot_produits',
            'lots',
            'ordre_production_lignes',
            'ordres_production',
            'bon_commande_lignes',
            'bons_commande',
            'produit_matiere_premiere',
            'produit_etapes',
            'produits',
            'mouvements_stock_matiere',
            'stock_matieres_premieres',
            'matieres_premieres_prix_historique',
            'matieres_premieres',
            'etapes_production',
            'emplacements',
            'hangars',
            'depots',
            'clients',
            'sequences',
        ];
        foreach ($tables as $t) {
            Schema::dropIfExists($t);
        }
    }
};
