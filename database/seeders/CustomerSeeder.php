<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::insert([
            [
                'name' => 'John Doe',
                'phone' => '081234567890',
            ],
            [
                'name' => 'Jane Smith',
                'phone' => '081987654321',
            ],
        ]);
    }
}
