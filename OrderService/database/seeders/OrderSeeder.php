<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Order::create([
            'user_id' => 1,
            'product_id' => 1,
            'qty' => 1,
            'total_price' => 25000000,
            'status' => 'pending'
        ]);

        \App\Models\Order::create([
            'user_id' => 1,
            'product_id' => 2,
            'qty' => 2,
            'total_price' => 1000000,
            'status' => 'processing'
        ]);

        \App\Models\Order::create([
            'user_id' => 2,
            'product_id' => 1,
            'qty' => 1,
            'total_price' => 25000000,
            'status' => 'completed'
        ]);

        \App\Models\Order::create([
            'user_id' => 3,
            'product_id' => 3,
            'qty' => 5,
            'total_price' => 500000,
            'status' => 'cancelled'
        ]);

        \App\Models\Order::create([
            'user_id' => 1,
            'product_id' => 1,
            'qty' => 1,
            'total_price' => 25000000,
            'status' => 'pending'
        ]);
    }
}
