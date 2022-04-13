<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRendezVousTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rendezvous', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disponibilite_id')->constrained();
            $table->time('heure_debut');
            $table->foreignId('probleme_id')->constrained();
            $table->string('nom_client', 200);
            $table->string('courriel', 250);
            $table->string('telephone', 15)->nullable();
            $table->text('description_rdv')->nullable();
            $table->integer('duree_avg')->nullable();
            $table->boolean('is_confirme')->default(false);
            $table->string('token', 300)->unique();
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
        Schema::dropIfExists('rendez_vous');
    }
}
