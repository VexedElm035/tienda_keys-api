<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Review;
use App\Models\Game;
use App\Models\User;



/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'seller_id' => User::inRandomOrder()->first()->id,
            'game_id' => Game::inRandomOrder()->first()->id,
            'rate' => $this->faker->numberBetween(1, 5),
            'rate_ux' => $this->faker->numberBetween(1, 5),
            'rate_time' => $this->faker->numberBetween(1, 5),
            'commentary' => $this->faker->sentence,
        ];
    }
}
