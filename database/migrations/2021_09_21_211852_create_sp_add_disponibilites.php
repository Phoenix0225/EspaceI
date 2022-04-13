<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpAddDisponibilites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE if exists `sp_add_disponibilites`;
                      CREATE PROCEDURE `sp_add_disponibilites`
                      (
                         IN pdebut DATETIME,
                         IN pfin DATETIME,
                         IN pendroit_id INTEGER,
                         IN putilisateur_id INTEGER,
                         IN pdate_max DATE
                      )
                      BEGIN

                      DECLARE pjusqua DATETIME;

                      SET pjusqua = DATE_ADD(pdate_max, INTERVAL 1 day);

                      WHILE pdebut < pjusqua DO
                            INSERT INTO `disponibilites`(endroit_id, debut, fin, utilisateur_id, created_at)
                                VALUES (pendroit_id, pdebut, pfin, putilisateur_id, NOW());

                      SET pdebut = DATE_ADD(pdebut, INTERVAL 7 day);
                      SET pfin = DATE_ADD(pfin, INTERVAL 7 day);

                      END WHILE;

                      END;";
        \DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sp_add_disponibilites');
    }
}
