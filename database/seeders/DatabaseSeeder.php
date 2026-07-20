<?php

namespace Database\Seeders;

use App\Models\BonCommandeLigne;
use App\Models\Client;
use App\Models\Depot;
use App\Models\EtapeProduction;
use App\Models\Hangar;
use App\Models\MatierePremiere;
use App\Models\Produit;
use App\Models\ProduitEtape;
use App\Models\ProduitMatierePremiere;
use App\Models\StockMatierePremiere;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 Seeding Nexus ERP...');

        // 1. Rôles (spatie/permission, via Shield)
        $roles = ['admin', 'commercial', 'production', 'magasinier'];
        foreach ($roles as $name) {
            Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@nexus-erp.dz'],
            [
                'name' => 'Admin Nexus',
                'password' => Hash::make('admin123'),
                'actif' => true,
                'poste' => 'Administrateur',
            ]
        );
        $admin->assignRole('admin');

        // 2. Dépôts & hangar
        $depotDefaut = Depot::firstOrCreate(['code' => 'DEP-PROD'], [
            'nom' => 'Dépôt Production', 'type' => 'mixte', 'adresse' => 'Atelier principal', 'actif' => true,
        ]);
        Hangar::firstOrCreate(
            ['nom' => 'Atelier', 'depot_id' => $depotDefaut->id],
            ['actif' => true]
        );

        $etapesData = [
            ['nom' => 'Découpe', 'ordre' => 1],
            ['nom' => 'Fraisage', 'ordre' => 2],
            ['nom' => 'Ponçage', 'ordre' => 3],
            ['nom' => 'Peinture', 'ordre' => 4],
            ['nom' => 'Montage', 'ordre' => 5],
            ['nom' => 'Contrôle', 'ordre' => 6],
            ['nom' => 'Emballage', 'ordre' => 7],
        ];
        foreach ($etapesData as $e) {
            EtapeProduction::firstOrCreate(['nom' => $e['nom']], ['ordre' => $e['ordre'], 'actif' => true]);
        }

        // 3. Dépôts matières / produits finis
        $depotMP = Depot::firstOrCreate(['code' => 'MP-01'], [
            'nom' => 'Magasin Matières Premières', 'type' => 'matiere', 'adresse' => 'Zone Stock MP', 'actif' => true,
        ]);
        $depotPF = Depot::firstOrCreate(['code' => 'PF-01'], [
            'nom' => 'Magasin Produits Finis', 'type' => 'produit_fini', 'adresse' => 'Zone Stock PF', 'actif' => true,
        ]);

        // 4. Matières premières + stock
        $matieresData = [
            ['nom' => 'Bois MDF 18mm', 'code' => 'MDF-18', 'unite' => 'm2', 'cout' => 4500.00],
            ['nom' => 'Bois MDF 25mm', 'code' => 'MDF-25', 'unite' => 'm2', 'cout' => 6200.00],
            ['nom' => 'Stratifié décoratif', 'code' => 'STRAT', 'unite' => 'm2', 'cout' => 3800.00],
            ['nom' => 'Quincaillerie', 'code' => 'QUIN01', 'unite' => 'kg', 'cout' => 1200.00],
            ['nom' => 'Charnière métal', 'code' => 'CHARN', 'unite' => 'piece', 'cout' => 350.00],
            ['nom' => 'Poignée aluminium', 'code' => 'POIGN', 'unite' => 'piece', 'cout' => 280.00],
            ['nom' => 'Profilé aluminium', 'code' => 'ALUM', 'unite' => 'm', 'cout' => 2200.00],
            ['nom' => 'Verre trempé', 'code' => 'VERRE', 'unite' => 'm2', 'cout' => 8500.00],
            ['nom' => 'Mousse assise', 'code' => 'MOUSS', 'unite' => 'm2', 'cout' => 1500.00],
            ['nom' => 'Tissu ameublement', 'code' => 'TISS', 'unite' => 'm', 'cout' => 2800.00],
            ['nom' => 'Peinture laque', 'code' => 'PEINT', 'unite' => 'litre', 'cout' => 3200.00],
            ['nom' => 'Vernis', 'code' => 'VERN', 'unite' => 'litre', 'cout' => 2400.00],
            ['nom' => 'Emballage carton', 'code' => 'CARTON', 'unite' => 'piece', 'cout' => 180.00],
            ['nom' => 'Roulette pivotante', 'code' => 'ROUL', 'unite' => 'piece', 'cout' => 450.00],
        ];
        foreach ($matieresData as $m) {
            $mat = MatierePremiere::firstOrCreate(['code' => $m['code']], [
                'nom' => $m['nom'], 'unite' => $m['unite'], 'cout_unitaire_moyen' => $m['cout'], 'actif' => true,
            ]);
            StockMatierePremiere::firstOrCreate(
                ['matiere_premiere_id' => $mat->id, 'depot_id' => $depotMP->id],
                ['quantite_disponible' => 100, 'quantite_reservee' => 0]
            );
        }

        // 5. Produits finis + BOM + gamme
        $etapes = EtapeProduction::orderBy('ordre')->get();
        $allMatieres = MatierePremiere::all();
        $mdf = $allMatieres->firstWhere('code', 'MDF-18');
        $quinc = $allMatieres->firstWhere('code', 'QUIN01');
        $carton = $allMatieres->firstWhere('code', 'CARTON');

        $produitsData = [
            ['nom' => 'Bureau Directeur Luxe', 'ref' => 'BDL-001', 'cat' => 'bureau', 'prix' => 185000, 'min' => 3],
            ['nom' => 'Bureau Standard', 'ref' => 'BST-001', 'cat' => 'bureau', 'prix' => 85000, 'min' => 5],
            ['nom' => 'Fauteuil Directeur', 'ref' => 'FAD-001', 'cat' => 'fauteuil', 'prix' => 75000, 'min' => 5],
            ['nom' => 'Chaise ergonomique', 'ref' => 'CHE-001', 'cat' => 'chaise', 'prix' => 45000, 'min' => 10],
            ['nom' => 'Table réunion 6p', 'ref' => 'TBR-001', 'cat' => 'table', 'prix' => 145000, 'min' => 2],
            ['nom' => 'Armoire 2 portes', 'ref' => 'ARM-001', 'cat' => 'armoire', 'prix' => 95000, 'min' => 4],
            ['nom' => 'Bibliothèque', 'ref' => 'BIB-001', 'cat' => 'rangement', 'prix' => 65000, 'min' => 3],
            ['nom' => 'Cloison bureau 120cm', 'ref' => 'CLO-001', 'cat' => 'cloison', 'prix' => 35000, 'min' => 10],
            ['nom' => 'Caisson 3 tiroirs', 'ref' => 'CAI-001', 'cat' => 'rangement', 'prix' => 38000, 'min' => 5],
            ['nom' => 'Table basse', 'ref' => 'TAB-001', 'cat' => 'table', 'prix' => 55000, 'min' => 3],
        ];

        foreach ($produitsData as $p) {
            $prod = Produit::firstOrCreate(['reference' => $p['ref']], [
                'nom' => $p['nom'], 'categorie' => $p['cat'], 'prix_vente' => $p['prix'],
                'tva_taux' => 19.0, 'stock_alerte_min' => $p['min'], 'actif' => true,
            ]);

            if ($mdf) {
                ProduitMatierePremiere::firstOrCreate(
                    ['produit_id' => $prod->id, 'matiere_premiere_id' => $mdf->id],
                    ['quantite' => 2.5]
                );
            }
            if ($quinc) {
                ProduitMatierePremiere::firstOrCreate(
                    ['produit_id' => $prod->id, 'matiere_premiere_id' => $quinc->id],
                    ['quantite' => 0.5]
                );
            }
            if ($carton && $p['prix'] > 50000) {
                ProduitMatierePremiere::firstOrCreate(
                    ['produit_id' => $prod->id, 'matiere_premiere_id' => $carton->id],
                    ['quantite' => 2]
                );
            }

            $i = 1;
            foreach ($etapes as $etape) {
                ProduitEtape::firstOrCreate(
                    ['produit_id' => $prod->id, 'etape_production_id' => $etape->id],
                    ['ordre' => $i, 'duree_minutes' => 15 + rand(0, 20)]
                );
                $i++;
            }
        }

        // 6. Clients
        $clientsData = [
            ['nom' => 'SONATRACH SPA', 'nif' => '099816012345678', 'email' => 'comptable@sonatrach.dz', 'tel' => '+21321600000', 'credit' => 50000000],
            ['nom' => 'Air Algérie SPA', 'nif' => '099816054321098', 'email' => 'achat@airalgerie.dz', 'tel' => '+21321500000', 'credit' => 20000000],
            ['nom' => 'Cevital SPA', 'nif' => '099816098765432', 'email' => 'fournisseurs@cevital.dz', 'tel' => '+21323380000', 'credit' => 15000000],
            ['nom' => 'BEA', 'nif' => '099816011223344', 'email' => 'marche@bea.dz', 'tel' => '+21321230000', 'credit' => 30000000],
            ['nom' => 'ENIEM SPA', 'nif' => '099816055667788', 'email' => 'achat@eniem.dz', 'tel' => '+21324810000', 'credit' => 10000000],
            ['nom' => 'Naftal SPA', 'nif' => '099816099001122', 'email' => 'logistique@naftal.dz', 'tel' => '+21321540000', 'credit' => 12000000],
            ['nom' => 'Sarl El Djazair Com', 'nif' => '099816077889900', 'email' => 'contact@eldjazair.dz', 'tel' => '+213555123456', 'credit' => 5000000],
            ['nom' => 'EURL Office Plus', 'nif' => '099816033445566', 'email' => 'achat@officeplus.dz', 'tel' => '+213770987654', 'credit' => 3000000],
            ['nom' => 'SARL Meubles Design', 'nif' => '099816011223355', 'email' => 'commercial@meubles-design.dz', 'tel' => '+213661234567', 'credit' => 8000000],
            ['nom' => 'EURL Pro Bureau', 'nif' => '099816044556677', 'email' => 'info@probureau.dz', 'tel' => '+213559876543', 'credit' => 4000000],
        ];
        foreach ($clientsData as $c) {
            Client::firstOrCreate(
                ['nif' => $c['nif']],
                [
                    'raison_sociale' => $c['nom'], 'email' => $c['email'], 'tel' => $c['tel'],
                    'adresse' => 'Alger Centre', 'plafond_credit' => $c['credit'], 'solde' => 0, 'actif' => true,
                ]
            );
        }

        $this->call(DemoDataSeeder::class);

        $this->command->info('✅ Seed terminé !');
        $this->command->info('   - ' . count($roles) . ' rôles (spatie)');
        $this->command->info('   - 1 admin (admin@nexus-erp.dz / admin123)');
        $this->command->info('   - ' . count($etapesData) . ' étapes de production');
        $this->command->info('   - 3 dépôts');
        $this->command->info('   - ' . count($matieresData) . ' matières premières');
        $this->command->info('   - ' . count($produitsData) . ' produits finis');
        $this->command->info('   - ' . count($clientsData) . ' clients');
    }
}
