<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\ProductRequest;
use App\Http\Resources\Inventory\ProductResource;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\Inventory\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponser, SharedFunctions;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        return $this->success($products->map(fn($product) =>
        ProductResource::make($product, ['id', 'ar_name', 'en_name', 'code'])));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $product = Product::create($request->validated());

        return $this->success(ProductResource::make($product));
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $product = $id == 1 ? Product::first() : Product::find($id);
        $product = $this->navigateRecord($product, $request);
        return $this->success(ProductResource::make($product));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return $this->success(ProductResource::make($product));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
