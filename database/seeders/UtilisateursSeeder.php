<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UtilisateursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('utilisateurs')->insert([
            [
                'id'=>1,
                'nom'=>'Application',
                'prenom'=>'Administrateur',
                'courriel'=>'admin@noreply.com',
                'telephone'=>'000-000-0000',
                'types_utilisateur_id'=>1,
                'is_admin' =>1,
                'password'=> Hash::make('Admin123!'),
            ],
        ]);
    }
}
