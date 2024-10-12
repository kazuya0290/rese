<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $areas = [
            "東京",
            "大阪",
            "福岡"
        ];

        foreach ($areas as $area) {
            DB::table('areas')->insert([
                'area' => $area,
            ]);
        }
    }
}
