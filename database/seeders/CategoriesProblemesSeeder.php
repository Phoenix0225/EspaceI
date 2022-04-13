<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesProblemesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('categories_problemes')->insert([
            [
                'id'=>1,
                'categorie'=>'Installation matériel'
            ],
            [
                'id'=>2,
                'categorie'=>'Installation logiciel'
            ],
            [
                'id'=>3,
                'categorie'=>'Formation'
            ],
            [
                'id'=>4,
                'categorie'=>'Cybersécurité'
            ]
        ]);
    }
}
