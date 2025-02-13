<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\UnitRequest;
use App\Http\Resources\Inventory\UnitResource;
use App\Http\Traits\ApiResponser;
use App\Models\Inventory\Product;
use App\Models\Inventory\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        info($request);
        $productId = $request->product_id;
        $showProductUnits = $request->show_product_units;
        $units = Unit::all();
        // to get only specific units depend on product Ids
        if ($productId) {
            $product = Product::find($productId);
            $productUnitsIds = $product->productUnits()->pluck('unit_id');
            if ($showProductUnits == 'true') {
                $units = $units->whereIn('id', $productUnitsIds);
            } else {
                $units = $units->whereNotIn('id', $productUnitsIds);
            }
        }

        return $this->success(UnitResource::collection($units));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UnitRequest $request)
    {
        $unit = Unit::create($request->validated());
        return $this->success(UnitResource::make($unit));
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit) {}


    /**
     * Update the specified resource in storage.
     */
    public function update(UnitRequest $request, Unit $unit)
    {
        $unit->update($request->validated());
        return $this->success(UnitResource::make($unit));
    }
}
