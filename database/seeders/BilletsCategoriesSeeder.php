<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BilletsCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('billet_categories')->insert([
            [
                'id'=>1,
                'categorie'=>'À définir'
            ],
            [
                'id'=>2,
                'categorie'=>'Installation matériel'
            ],
            [
                'id'=>3,
                'categorie'=>'Installation logiciel'
            ],
            [
                'id'=>4,
                'categorie'=>'Formation'
            ],
            [
                'id'=>5,
                'categorie'=>'Cybersécurité'
            ],
            [
                'id'=>6,
                'categorie'=>'Autre'
            ],
        ]);
    }
}
