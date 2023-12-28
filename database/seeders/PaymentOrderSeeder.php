<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\PaymentOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    PaymentOrder::factory()->count(50)->create();
    }
}
