<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group;>
 */
class GroupFactory extends Factory
{
    protected $model = Group::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'report_type' => $this->faker->randomElement(['bs', 'pl'])
        ];
    }
}

