<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->decimal('taux_tva', 5, 2)->default(19.00)->after('montant_ttc')
                ->comment('Taux de TVA applicable à la facture (éditable, défaut 19%)');
            $table->text('montant_ttc_lettres')->nullable()->after('montant_ttc')
                ->comment('Montant TTC écrit en lettres (norme comptable)');
        });
    }

    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropColumn(['taux_tva', 'montant_ttc_lettres']);
        });
    }
};
