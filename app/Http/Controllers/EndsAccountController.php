<?php

namespace App\Http\Controllers;

use App\Http\Resources\EndsAccountResource;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\EndsAccount;
use Illuminate\Http\Request;

class EndsAccountController extends Controller
{
    use ApiResponser, SharedFunctions;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $endsAccount = EndsAccount::all();

        return $this->success(EndsAccountResource::collection($endsAccount));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ar_name' => 'required|string',
            'en_name' => 'required|string',
        ]);

        $endAccount = EndsAccount::create($validated);

        return $this->success(EndsAccountResource::make($endAccount));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $endAccount = EndsAccount::findOrFail($id);

        $endAccount = $this->navigateRecord($endAccount, $request);

        return $this->success(EndsAccountResource::make($endAccount));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $endAccount = EndsAccount::findOrFail($id);
        $validated = $request->validate([
            'ar_name' => 'required|string',
            'en_name' => 'required|string',
        ]);

        $endAccount->update($validated);

        return $this->success(EndsAccountResource::make($endAccount));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        EndsAccount::findOrFail($id)->delete();

        return $this->success(null);
    }
}
