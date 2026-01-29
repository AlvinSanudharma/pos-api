<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_transactions()
    {
        $user = User::factory()->create();
        Transaction::factory()->count(5)->create();

        $response = $this->actingAs($user)->getJson('/api/v1/transactions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'items' => [
                        '*' => ['id', 'code', 'total', 'items']
                    ],
                    'pagination'
                ]
            ]);
    }

    public function test_can_create_transaction()
    {
        $user = User::factory()->create();
        // Create Product with stock
        $product = Product::factory()->create([
            'price' => 100,
            'stock' => 10
        ]);
        
        $customer = Customer::factory()->create();

        $data = [
            'customer_id' => $customer->id,
            'tax' => 10,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'price' => 100
                ]
            ]
        ];

        $response = $this->actingAs($user)->postJson('/api/v1/transactions', $data);

        $response->assertStatus(201)
            ->assertJsonPath('data.total', 210); // (100 * 2) + 10

        // Assert Stock Decremented
        $this->assertEquals(8, $product->fresh()->stock);
        
        // Assert Database
        $this->assertDatabaseHas('transactions', [
            'customer_id' => $customer->id,
            'subtotal' => 200,
            'tax' => 10,
            'total' => 210
        ]);
        
        $this->assertDatabaseHas('transaction_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100
        ]);
    }

    public function test_cannot_create_transaction_with_insufficient_stock()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'stock' => 5
        ]);

        $data = [
            'tax' => 0,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 10, // Exceeds stock
                    'price' => 100
                ]
            ]
        ];

        $response = $this->actingAs($user)->postJson('/api/v1/transactions', $data);

        $response->assertStatus(400); // Expecting Bad Request from our try catch logic
        
        // Assert Stock Unchanged
        $this->assertEquals(5, $product->fresh()->stock);
    }
}
