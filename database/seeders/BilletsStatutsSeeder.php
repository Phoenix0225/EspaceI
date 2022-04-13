<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BilletsStatutsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('billet_statuts')->insert([
            [
                'id'=>1,
                'statut'=>'Nouveau'
            ],
            [
                'id'=>2,
                'statut'=>'En attente'
            ],
            [
                'id'=>3,
                'statut'=>'En progrès'
            ],
            [
                'id'=>4,
                'statut'=>'Résolu'
            ],
            [
                'id'=>5,
                'statut'=>'Fermé'
            ]
        ]);
    }
}
