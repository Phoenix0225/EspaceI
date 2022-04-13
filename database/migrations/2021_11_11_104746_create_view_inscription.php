<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewInscription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $view = ("

        DROP VIEW IF EXISTS inscriptions;

        CREATE VIEW inscriptions AS
        SELECT  e.id
         , COUNT(ep.token) as nb_incription
         , CASE WHEN nb_places IS NULL
                    THEN 99999999
                ELSE nb_places
            END AS place
         , (CASE WHEN nb_places IS NULL
                    THEN 99999999
                ELSE nb_places
            END) - COUNT(ep.token) AS place_restante
    FROM evenement_participant ep
    RIGHT JOIN evenements e ON e.id = ep.evenement_id

    GROUP BY id, nb_places;");
    \DB::unprepared($view);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS inscriptions');
    }
}
