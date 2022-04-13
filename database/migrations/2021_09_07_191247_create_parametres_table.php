<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParametresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parametres', function (Blueprint $table) {
            $table->id();
            $table->integer('duree_plage_horaire')->nullable();
            $table->integer('duree_rdv_max')->nullable();
            $table->time('rdv_heure_debut')->nullable();
            $table->time('rdv_heure_fin')->nullable();
            $table->integer('billet_statut_nouveau')->nullable();
            $table->integer('billet_statut_fermee')->nullable();
            $table->integer('nb_evenements_accueil')->nullable();
            $table->integer('nb_dispo_dashboard')->nullable();
            $table->text('txt_a_propos_accueil')->nullable();
            $table->string('api_key', 100)->nullable();
            $table->string('channel_id', 100)->nullable();
            $table->string('lien_channel', 255)->nullable();
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
        Schema::dropIfExists('parametres');
    }
}
