<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClosingAccountResource;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\ClosingAccount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ar_name' => 'required|string|unique:closing_accounts',
            'en_name' => 'required|string|unique:closing_accounts',
        ]);

        $ClosingAccount = ClosingAccount::create($validated);

        return $this->success(ClosingAccountResource::make($ClosingAccount));
    }

    /**
     * Display the specified resource.
     */
    public function show(ClosingAccount $closingAccount, Request $request)
    {
        $closingAccount = $this->navigateRecord($closingAccount, $request);

        return $this->success(ClosingAccountResource::make($closingAccount));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClosingAccount $closingAccount)
    {
        $validated = $request->validate([
            'ar_name' => Rule::unique('closing_accounts', 'ar_name')->ignore($closingAccount->id),
            'en_name' => Rule::unique('closing_accounts', 'en_name')->ignore($closingAccount->id),
        ]);

        $closingAccount->update($validated);

        return $this->success(ClosingAccountResource::make($closingAccount));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClosingAccount $closingAccount)
    {
        // Todo: Delete with conditions
        // $closingAccount->delete();

        return $this->success(null);
    }
}
