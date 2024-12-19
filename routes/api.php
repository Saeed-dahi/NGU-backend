<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountInformationController;
use App\Http\Controllers\ClosingAccountController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\LoginController;
use App\Models\ClosingAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::post('/login', [LoginController::class, 'loginUserApi']);

    Route::middleware([
        // 'auth:sanctum',
        'language_middleware'
    ])->group(function () {

        Route::get('/profile', function (Request $request) {
            return  $request->user();
        });

        // Accounts => {closing, account, account information}
        Route::apiResource('closing-account', ClosingAccountController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
        Route::get('closing-account-sts', [ClosingAccountController::class, 'closingAccountSts']);
        Route::apiResource('account', AccountController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
        Route::get('accounts-name', [AccountController::class, 'getAccountsNameWithCode']);
        Route::get('account-statement/{account}', [AccountController::class, 'accountStatement']);
        Route::apiResource('account-information', AccountInformationController::class)->only(['show']);
        // To upload file cuz put did not work
        Route::Post('account-information/{account_information}', [AccountInformationController::class, 'update']);
        Route::get('search-account', [AccountController::class, 'searchAccount']);
        Route::get('get-suggestion-code', [AccountController::class, 'getSuggestionCode']);


        Route::apiResource('journal', JournalController::class);
    });
});
