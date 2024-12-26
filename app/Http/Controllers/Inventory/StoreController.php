<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreRequest;
use App\Http\Resources\Inventory\StoreResource;
use App\Http\Traits\ApiResponser;

use App\Models\Inventory\Store;

class StoreController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stores = Store::all();
        return $this->success(StoreResource::collection($stores));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $store = Store::create($request->validated());

        return $this->success(StoreResource::make($store));
    }

    /**
     * Display the specified resource.
     */
    public function show(Store $store) {}


    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRequest $request, Store $store)
    {
        $store->update($request->validated());

        return $this->success(StoreResource::make($store));
    }
}
