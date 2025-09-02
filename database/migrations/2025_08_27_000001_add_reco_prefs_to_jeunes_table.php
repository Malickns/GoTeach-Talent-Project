<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jeunes', function (Blueprint $table) {
            if (!Schema::hasColumn('jeunes', 'types_contrat_preferes')) {
                $table->json('types_contrat_preferes')->nullable()->after('preferences_emploi');
            }
            if (!Schema::hasColumn('jeunes', 'secteurs_preferes')) {
                $table->json('secteurs_preferes')->nullable()->after('types_contrat_preferes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jeunes', function (Blueprint $table) {
            if (Schema::hasColumn('jeunes', 'secteurs_preferes')) {
                $table->dropColumn('secteurs_preferes');
            }
            if (Schema::hasColumn('jeunes', 'types_contrat_preferes')) {
                $table->dropColumn('types_contrat_preferes');
            }
        });
    }
};


