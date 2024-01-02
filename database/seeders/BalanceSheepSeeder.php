<?php

namespace Database\Seeders;

use App\Models\BalanceSheet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BalanceSheepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BalanceSheet::factory()->count(50)->create();
    }
}
