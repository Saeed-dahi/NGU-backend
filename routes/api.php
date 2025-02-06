<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\AccountInformationController;
use App\Http\Controllers\ClosingAccount\ClosingAccountController;
use App\Http\Controllers\Inventory\CategoryController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\ProductUnitController;
use App\Http\Controllers\Inventory\StoreController;
use App\Http\Controllers\Inventory\UnitController;
use App\Http\Controllers\invoice\InvoiceController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\LoginController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::post('/login', [LoginController::class, 'loginUserApi']);

    Route::middleware(['language_middleware'])->group(function () {

        Route::get('/profile', function (Request $request) {
            return  $request->user();
        });

        // Accounts => {closing, account, account information}
        Route::prefix('')->group(function () {
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
        });

        // Inventory
        Route::prefix('')->group(function () {
            Route::apiResource('store', StoreController::class)->only(['index', 'store', 'update']);
            Route::apiResource('category', CategoryController::class)->only(['index', 'store', 'update']);
            Route::apiResource('unit', UnitController::class)->only(['index', 'store', 'update']);
            Route::apiResource('product', ProductController::class)->only(['index', 'show', 'store']);
            Route::post('product/{product}', [ProductController::class, 'update']);
            Route::apiResource('product-unit', ProductUnitController::class)->only(['store', 'update']);

            Route::get('invoice/create', [InvoiceController::class, 'create']);
            Route::apiResource('invoice', InvoiceController::class)->only(['store', 'update', 'show', 'index']);
        });

        Route::apiResource('journal', JournalController::class);
    });
});
