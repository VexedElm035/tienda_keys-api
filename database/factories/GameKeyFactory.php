<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\GameKey;
use App\Models\Game;
use App\Models\User;
use App\Models\Sale;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameKey>
 */
class GameKeyFactory extends Factory
{
    protected $model = GameKey::class;

    public function definition()
    {
        return [
            //'game_id => Game::factory()'
            'game_id' => Game::inRandomOrder()->first()->id,
            'state' => $this->faker->randomElement(['available', 'sold']),
            'region' => $this->faker->country,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'tax' => $this->faker->randomFloat(2, 1, 10),
            'delivery_time' => $this->faker->randomElement(['instant', '24 hours']),
            'seller_id' => User::inRandomOrder()->first()->id,
            'platform' => $this->faker->randomElement(['PC', 'Xbox', 'PlayStation']),
            'sale_id' => Sale::inRandomOrder()->first()->id,
        ];
    }
}

