<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ReservationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
{
    DB::table('reservations')->insert([
        'shop_id' => 1,
        'reservation_date' => '2024-10-15',
        'reservation_time' => '18:00:00',
        'number_of_people' => 4,
    ]);
}
}
