<?php

namespace Database\Factories;

use App\Models\ExchangeRate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExchangeRate;>
 */
class ExchangeRateFactory extends Factory
{
    protected $model = ExchangeRate::class;
    public function definition(): array
    {
        return [
            'jpy' => $this->faker->randomFloat(4, 100, 150),
            'usd' => $this->faker->randomFloat(4, 0.8, 1.2), 
            'exchange_rate_month' => $this->faker->dateTimeThisMonth(),
        
        ];
    }
}
