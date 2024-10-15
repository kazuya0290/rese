<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
{
    DB::table('details')->insert([
        'shop_id' => 1,
        'name' => 'Sample Shop',
        'description' => 'This is a sample shop description.',
        'image' => 'sample_image_url.jpg',
    ]);
}
}
