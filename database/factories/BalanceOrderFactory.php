<?php

namespace Database\Factories;

use App\Models\BalanceSheet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BalanceSheet;>
 */
class BalanceSheetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = BalanceSheet::class;
    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(4, 1, 10000),
            'category_id' => $this->faker->numberBetween(1, 100), 
            'bs_month_year' => $this->faker->text(50),
        ];
    }
}