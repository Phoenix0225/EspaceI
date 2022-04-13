<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ParametresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('parametres')->insert([
            [
                'id'=>1,
                'duree_plage_horaire'=>5,
                'duree_rdv_max'=>30,
                'rdv_heure_debut'=>'08:00',
                'rdv_heure_fin'=>'17:45',
                'nb_evenements_accueil'=>3,
                'nb_dispo_dashboard'=>3,
                'api_key'=>'AIzaSyCAWuv2Xv6saIkfhMrbzVOLcGy5tCTJH5Q',
                'channel_id'=>'UCJ9905MRHxwLZ2jeNQGIWxA',
                'lien_channel'=>'https://www.youtube.com/c/MicrosoftMechanicsSeries',
            ]
        ]);
    }
}
