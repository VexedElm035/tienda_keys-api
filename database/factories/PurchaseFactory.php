<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Purchase;
use App\Models\User;
use App\Models\GameKey;



/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'total' => $this->faker->randomFloat(2, 10, 500),
            'pay_method' => $this->faker->randomElement(['credit_card', 'paypal']),
            'tax' => $this->faker->randomFloat(2, 1, 50),
            'state' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
            'purchase_date' => $this->faker->dateTimeThisYear(),
            'key_id' => GameKey::inRandomOrder()->first()->id,
        ];
    }
}

