<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('outlet')->paginate(10);
        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());
        
        return response()->json([
            'message' => 'Product created successfully',
            'product' => new ProductResource($product->load('outlet'))
        ], 201);
    }

    public function show(Product $product)
    {
        return new ProductResource($product->load('outlet'));
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        
        return response()->json([
            'message' => 'Product updated successfully',
            'product' => new ProductResource($product->load('outlet'))
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        
        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}