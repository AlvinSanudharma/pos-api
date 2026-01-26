<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetProductCategoriesRequest;
use App\Http\Resources\PaginatedResource;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetProductCategoriesRequest $request)
    {
        $categories = ProductCategory::search($request->search)
                                        ->latest()
                                        ->paginate($request->limit ?? 10);
        
        return ApiResponse::success(new PaginatedResource($categories, ProductCategoryResource::class), 'Product Categories List');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
