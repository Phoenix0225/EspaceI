<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvenementParticipant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evenement_participant', function (Blueprint $table) {
            $table->foreignId('participant_id')->constrained();
            $table->foreignId('evenement_id')->constrained();
            $table->boolean('isPresent')->default(0);
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
        Schema::dropIfExists('evenement_participant');
    }
}
