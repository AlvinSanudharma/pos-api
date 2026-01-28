<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetProductCategoriesRequest;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Resources\PaginatedResource;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetProductCategoriesRequest $request)
    {
        $categories = ProductCategory::search($request->search)
                                        ->latest('id')
                                        ->paginate($request->limit ?? 10);
        
        return ApiResponse::success(new PaginatedResource($categories, ProductCategoryResource::class), 'Product Categories List');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductCategoryRequest $request)
    {
        $category = ProductCategory::create($request->validated());

        return ApiResponse::success(
            new ProductCategoryResource($category),
            'Product Category Created Successfully',
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = ProductCategory::find($id);

        if (!$category) {
            return ApiResponse::error('Product Category Not Found', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(
            new ProductCategoryResource($category),
            'Product Category Detail Successfully',
            Response::HTTP_OK
        );
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
