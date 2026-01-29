<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetTransactionsRequest;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\PaginatedResource;
use App\Http\Resources\TransactionResource;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetTransactionsRequest $request)
    {
        $transactions = Transaction::with(['customer', 'items.product'])
            ->search($request->search)
            ->latest('id')
            ->paginate($request->limit ?? 10);

        return ApiResponse::success(new PaginatedResource($transactions, TransactionResource::class), 'Transactions List');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $subtotal = 0;
            $itemsData = [];

            // Generate Transaction Code
            // Format: TRX-TIMESTAMP-RANDOM
            $code = 'TRX-' . now()->timestamp . '-' . Str::upper(Str::random(5));

            foreach ($validated['items'] as $item) {
                // Lock product for update to prevent race conditions
                $product = Product::where('id', $item['product_id'])->lockForUpdate()->first();

                if (!$product) {
                    throw new \Exception("Product with ID {$item['product_id']} not found.");
                }

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}. Requested: {$item['quantity']}, Available: {$product->stock}");
                }

                // Deduct Stock
                $product->stock -= $item['quantity'];
                $product->save();

                // Calculate item subtotal
                $itemSubtotal = $item['price'] * $item['quantity'];
                $subtotal += $itemSubtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemSubtotal,
                ];
            }

            // Calculate Total
            // Assuming tax is a flat amount or percentage input. 
            // Based on ERD, 'tax' is decimal(15,2), request sends 'tax' value.
            // If request 'tax' is the tax amount itself:
            $tax = $validated['tax']; 
            // If request tax is percentage, we would calculate. Assuming it's amount based on request 'numeric|min:0' validation and simplified logic.
            // However, typical POS sends tax AMOUNT or percentage. Let's assume it's the tax amount provided by frontend.
            
            $total = $subtotal + $tax;

            // Create Transaction
            $transaction = Transaction::create([
                'code' => $code,
                'customer_id' => $validated['customer_id'] ?? null,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
            ]);

            // Create Transaction Items
            foreach ($itemsData as $data) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $data['product_id'],
                    'price' => $data['price'],
                    'quantity' => $data['quantity'],
                    'subtotal' => $data['subtotal'],
                ]);
            }

            DB::commit();

            // Reload relationships for response
            $transaction->load(['customer', 'items.product']);

            return ApiResponse::success(
                new TransactionResource($transaction),
                'Transaction Created Successfully',
                Response::HTTP_CREATED
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = Transaction::with(['customer', 'items.product'])->find($id);

        if (!$transaction) {
            return ApiResponse::error('Transaction Not Found', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new TransactionResource($transaction),
            'Transaction Detail Successfully',
        );
    }
}
