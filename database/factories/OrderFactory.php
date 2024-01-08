<?php

namespace Database\Factories;

use App\Models\ExchangeRate;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order;>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'company_name' => $this->faker->company,
            'jpy' => $this->faker->randomFloat(2, 1000, 10000), // Example range for JPY
            'usd' => $this->faker->randomFloat(2, 500, 5000), // Example range for USD
            'vnd' => $this->faker->randomFloat(2, 100, 5000),
            'exchange_rate_id' => ExchangeRate::factory(),
            'order_date' => $this->faker->dateTimeBetween('-1 year', 'now'), 
        ];
    }
}
