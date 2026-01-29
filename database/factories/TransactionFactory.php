<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'TRX-' . time() . '-' . Str::upper(Str::random(5)),
            'customer_id' => Customer::factory(),
            'subtotal' => 100,
            'tax' => 10,
            'total' => 110,
        ];
    }
}
