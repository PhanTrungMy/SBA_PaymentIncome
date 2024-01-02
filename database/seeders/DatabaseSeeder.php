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
           // UserSeeder::class,
         GroupSeeder::class,
         categorySeeder::class, 
        ExchangeRateSeeder::class,
         OrderSeeder::class,
         PaymentOrderSeeder::class,
         OutsourcingSeeder::class,
         PaymentSeeder::class,  
            BalanceSheepSeeder::class,
        ]);
    }
}
