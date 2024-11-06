<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'shop_id' => Shop::inRandomOrder()->first()->id,
            'date' => $this->faker->date(),
            'time' => $this->faker->time(),
            'number_of_people' => $this->faker->randomDigitNotZero(),
        ];
    }
}
