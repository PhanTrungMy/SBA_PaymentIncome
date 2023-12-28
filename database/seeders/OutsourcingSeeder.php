<?php

namespace Database\Seeders;

use App\Models\Outsourcing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OutsourcingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    Outsourcing::factory()->count(50)->create();
    }
}
