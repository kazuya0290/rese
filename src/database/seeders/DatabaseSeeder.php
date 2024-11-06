<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Reservation;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AreasTableSeeder::class,
            GenresTableSeeder::class,
            ShopsTableSeeder::class
        ]);
            User::factory(35)->create();
            Reservation::factory(35)->create();
    }
}