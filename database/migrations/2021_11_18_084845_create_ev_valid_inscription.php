<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvValidInscription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $event = ("
        DROP EVENT IF EXISTS ev_valid_inscription;
        CREATE EVENT ev_valid_inscription
            ON SCHEDULE EVERY 6 HOUR
            STARTS '2021-01-01 00:00:00'
            DO
                CALL sp_cancel_not_confirm_par_event();");
        \DB::unprepared($event);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP EVENT IF EXISTS ev_valid_inscription');
    }
}
