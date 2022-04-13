<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantEvenementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_evenement', function (Blueprint $table) {
            $table->foreignId('participant_id')->constrained();
            $table->foreignId('evenement_id')->constrained();
            $table->boolean('isPresent')->nullable();
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
        Schema::dropIfExists('participant_evenement');
    }
}
