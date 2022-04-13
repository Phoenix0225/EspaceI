<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTgTokenRendezvous extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $trigger= "CREATE TRIGGER tbi_token_rendezvous
        BEFORE INSERT
        ON rendezvous FOR EACH ROW
        BEGIN
        DECLARE encrypt varchar(300);

        SET encrypt = CONCAT(NEW.courriel, '|', NEW.disponibilite_id, '|', NEW.created_at);
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
        Schema::dropIfExists('tg_token_rendezvous');
    }
}
