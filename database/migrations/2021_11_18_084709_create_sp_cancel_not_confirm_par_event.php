<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpCancelNotConfirmParEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = ("
        DROP PROCEDURE IF EXISTS sp_cancel_not_confirm_par_event;
        CREATE PROCEDURE `sp_cancel_not_confirm_par_event` ()
        BEGIN
            DELETE
            FROM evenement_participant
            WHERE isPresent = 0 AND created_at <= DATE_ADD(NOW(), INTERVAL -24 HOUR);
        END;");
        \DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP PROCEDURE IF EXISTS sp_cancel_not_confirm_par_event');
    }
}
