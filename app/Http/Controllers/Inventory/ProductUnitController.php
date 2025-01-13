<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\ProductUnitRequest;
use App\Http\Resources\Inventory\ProductUnitResource;
use App\Http\Traits\ApiResponser;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductUnit;

class ProductUnitController extends Controller
{
    use ApiResponser;

    public function index() {}

    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductUnitRequest $request)
    {
        // TODO: Refactor this function
        $baseUnit = $request->base_product_unit_id;
        if ($baseUnit) {
            $product = Product::findOrFail($request->product_id);
            $productUnit = $product->productUnits()->where('id', $request->base_product_unit_id)->first();

            if (!$productUnit || $productUnit->subUnit()->count() > 0) {
                return $this->error(null, __('lang.error_unit_type'), 400);
            }
        }

        $productUnit = ProductUnit::create($request->validated());
        return $this->success(ProductUnitResource::make($productUnit));
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductUnit $productUnit) {}

    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUnitRequest $request, ProductUnit $productUnit)
    {
        info('ssssss');
        info($request);
        $productUnit->update($request->validated());

        return $this->success(ProductUnitResource::make($productUnit));
    }

    public function destroy(string $id) {}
}
