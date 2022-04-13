<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TypesUtilisateursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('types_utilisateurs')->insert([
            [
                'id'=>1,
                'type'=>'Enseignant'
            ],
            [
                'id'=>2,
                'type'=>'Étudiant'
            ],
            [
                'id'=>3,
                'type'=>'Personnel de soutient'
            ]
        ]);
    }
}
