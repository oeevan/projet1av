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
        Schema::table('games', function (Blueprint $table) {
            // Bonus 3 (S5) : jaquette envoyée par fichier, stockée sur le disque public
            $table->string('cover_path')->nullable()->after('cover_url');
            $table->string('cover_url')->nullable()->change(); // l'URL devient facultative si un fichier est fourni
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('cover_path');
            $table->string('cover_url')->nullable(false)->change();
        });
    }
};
