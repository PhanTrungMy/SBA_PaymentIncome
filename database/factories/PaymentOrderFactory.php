<?php

namespace Database\Factories;

use App\Models\ExchangeRate;
use App\Models\PaymentOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentOrder;>
 */
class PaymentOrderFactory extends Factory
{
 
    protected $model = PaymentOrder::class;
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'company_name' => $this->faker->company,
            'jpy' => $this->faker->randomFloat(2, 100, 10000), 
            'usd' => $this->faker->randomFloat(2, 50, 5000), 
            'exchange_rate_id' => ExchangeRate::factory(),
            'payment_date' => $this->faker->dateTimeBetween('-1 year', 'now'), 
        ];
    }
}
