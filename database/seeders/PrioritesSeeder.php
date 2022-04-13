<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PrioritesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('priorites')->insert([
            [
                'id'=>1,
                'priorite'=>'Urgente'
            ],
            [
                'id'=>2,
                'priorite'=>'Haute'
            ],
            [
                'id'=>3,
                'priorite'=>'Normale'
            ],
            [
                'id'=>4,
                'priorite'=>'Basse'
            ]
        ]);
    }
}
