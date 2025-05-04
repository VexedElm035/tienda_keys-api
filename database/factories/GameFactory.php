<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Game;
use App\Models\Genre;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */

class GameFactory extends Factory
{
    protected $model = Game::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'img' => $this->faker->imageUrl(200, 300),
            'description' => $this->faker->paragraph,
            'launch_date' => $this->faker->date(),
            'publisher' => $this->faker->company,
            'available_platforms' => $this->faker->randomElement(['Steam', 'EpicGames', 'PS4', 'PS5', 'XboxOne', 'XboxSeries', 'Switch']),
            'genre_id' => Genre::inRandomOrder()->first()->id,
        ];
    }
}

