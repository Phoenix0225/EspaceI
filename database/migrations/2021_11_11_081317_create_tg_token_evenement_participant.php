<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTgTokenEvenementParticipant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $trigger= "CREATE TRIGGER tbi_token_evenement_participant
        BEFORE INSERT
        ON evenement_participant FOR EACH ROW
        BEGIN
        DECLARE tCourriel varchar(200);
        DECLARE encrypt varchar(300);
        SELECT courriel INTO tCourriel FROM participants WHERE id = NEW.participant_id;

        SET encrypt = CONCAT(tCourriel, '|', NEW.participant_id, '|', NEW.evenement_id);
        SET NEW.token = MD5(encrypt);
        END;";

    \DB::unprepared($trigger);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('tg_token_evenement_participant');
    }
}
