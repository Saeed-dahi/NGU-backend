<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClosingAccountResource;

use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\ClosingAccount;

use Illuminate\Http\Request;

class ClosingAccountController extends Controller
{
    use ApiResponser, SharedFunctions;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ClosingAccounts = ClosingAccount::all();

        return $this->success(ClosingAccountResource::collection($ClosingAccounts));
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

        $ClosingAccount = ClosingAccount::create($validated);

        return $this->success(ClosingAccountResource::make($ClosingAccount));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $ClosingAccount = ClosingAccount::findOrFail($id);

        $ClosingAccount = $this->navigateRecord($ClosingAccount, $request);

        return $this->success(ClosingAccountResource::make($ClosingAccount));
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
        $ClosingAccount = ClosingAccount::findOrFail($id);
        $validated = $request->validate([
            'ar_name' => 'required|string',
            'en_name' => 'required|string',
        ]);

        $ClosingAccount->update($validated);

        return $this->success(ClosingAccountResource::make($ClosingAccount));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ClosingAccount::findOrFail($id)->delete();

        return $this->success(null);
    }
}
