<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\ExchangeRate;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment;>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word,
            'cost' => $this->faker->randomFloat(2, 10, 500), 
            'currency_type' => $this->faker->randomElement(['USD', 'JPY', 'EUR']),
            'note' => $this->faker->text,
            'invoice' => $this->faker->text(50),
            'pay' => $this->faker->text(50),
            'category_id' => Category::factory(),
            'exchange_rate_id' => ExchangeRate::factory(),
            'payment_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
