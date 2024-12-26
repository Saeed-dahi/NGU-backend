<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\CategoryRequest;
use App\Http\Resources\Inventory\CategoryResource;
use App\Http\Traits\ApiResponser;
use App\Models\Inventory\Category;

class CategoryController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();

        return $this->success(CategoryResource::collection($categories));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return $this->success(CategoryResource::make($category));
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoryRequest $category)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return $this->success(CategoryResource::make($category));
    }
}
