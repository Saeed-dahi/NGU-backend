<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountInformation\AccountInformationRequest;
use App\Http\Resources\AccountInformationResource;
use App\Http\Traits\ApiResponser;
use App\Models\Account;
use App\Models\AccountInformation;

class AccountInformationController extends Controller
{
    use ApiResponser;

    /**
     * Display the specified resource.
     */
    public function show(Account $accountInformation)
    {
        $accountInformation = $accountInformation->AccountInformation;

        return $this->success(AccountInformationResource::make($accountInformation));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AccountInformationRequest $request, AccountInformation $accountInformation)
    {
        // to Edit file
        $requestData = array_merge($request->all(), ['file' => []]);
        $accountInformation->update($requestData);

        return $this->success(AccountInformationResource::make($accountInformation));
    }
}
