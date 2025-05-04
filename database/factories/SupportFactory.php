<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Support;
use App\Models\Game;
use App\Models\User;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Support>
 */
class SupportFactory extends Factory
{
    protected $model = Support::class;

    public function definition()
    {
        return [
            'user_id_seller' => User::inRandomOrder()->first()->id,
            'user_id_buyer' => User::inRandomOrder()->first()->id,
            'issue' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'state' => $this->faker->randomElement(['open', 'resolved', 'pending']),
            'game_id' => Game::inRandomOrder()->first()->id,
        ];
    }
}

