<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBilletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billets', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 50);
            $table->string('nom_client', 200);
            $table->string('courriel', 250);
            $table->string('telephone', 15)->nullable();
            $table->text('description_billet')->nullable();
            $table->foreignId('priorite_id')->nullable()->constrained();
            $table->foreignId('billet_statut_id')->constrained();
            $table->foreignId('billet_categorie_id')->nullable()->constrained();
            $table->foreignId('utilisateur_id')->nullable()->constrained();
            $table->date('date_limite')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billets');
    }
}
