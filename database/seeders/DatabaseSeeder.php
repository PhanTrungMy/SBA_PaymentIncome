<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
         AdminSeeder::class,
         GroupSeeder::class,
         categorySeeder::class, 
        //  UserSeeder::class,
         PaymentSeeder::class,
         OrderSeeder::class,
         ExchangeRateSeeder::class,
         PaymentOrderSeeder::class,
         OutsourcingSeeder::class,
         BalanceSheepSeeder::class
        ]);
    }
}
