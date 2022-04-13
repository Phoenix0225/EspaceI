<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvenementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 100);
            $table->text('description');
            $table->foreignId('endroit_id')->nullable()->constrained();
            $table->string('url_zoom', 200)-> nullable();
            $table->string('lien_image', 200)-> nullable();
            $table->dateTime('date_heure')-> nullable();
            $table->integer('duree')-> nullable();
            $table->integer('nb_places')-> nullable();
            $table->foreignId('types_evenement_id')->constrained();
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
        Schema::dropIfExists('evenements');
    }
}
