<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ParametresSeeder::class);
        $this->call(TypesUtilisateursSeeder::class);
        $this->call(UtilisateursSeeder::class);
        $this->call(BilletsStatutsSeeder::class);
        $this->call(PrioritesSeeder::class);
        $this->call(BilletsCategoriesSeeder::class);
        $this->call(CategoriesProblemesSeeder::class);
    }
}
