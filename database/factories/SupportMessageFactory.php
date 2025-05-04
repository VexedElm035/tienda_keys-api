<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SupportMessage;
use App\Models\User;
use App\Models\Support;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SupportMessage>
 */
class SupportMessageFactory extends Factory
{
    protected $model = SupportMessage::class;

    public function definition()
    {
        return [
            'ticket_id' => Support::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'answer' => $this->faker->paragraph,

        ];
    }
}

