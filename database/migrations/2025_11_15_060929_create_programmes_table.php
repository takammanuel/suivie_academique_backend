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
    Schema::create('programme', function (Blueprint $table) {
    $table->id();

    // code_ec doit être string car il référence ec.code_ec
    $table->string("code_ec", 20);
    $table->foreign("code_ec")->references("code_ec")->on("ec")->onDelete("cascade");

    $table->unsignedBigInteger("code_salle");
    $table->foreign("code_salle")->references("id")->on("salles")->onDelete("cascade");

    $table->unsignedBigInteger("code_personel");
    $table->foreign("code_personel")->references("id")->on("personnels")->onDelete("cascade");

    $table->date("date");
    $table->time("heure_debut");
    $table->time("heure_fin");
    $table->integer("nombre_dheure"); // ⚠️ pas d'apostrophe dans le nom de colonne
    $table->string("statut");
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programme');
    }
};
