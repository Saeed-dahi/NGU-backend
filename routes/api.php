<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\AccountInformationController;
use App\Http\Controllers\AdjustmentNote\AdjustmentNoteController;
use App\Http\Controllers\AdjustmentNote\AdjustmentNoteItemController;
use App\Http\Controllers\ChequeController;
use App\Http\Controllers\ClosingAccount\ClosingAccountController;
use App\Http\Controllers\Inventory\CategoryController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\ProductUnitController;
use App\Http\Controllers\Inventory\StoreController;
use App\Http\Controllers\Inventory\UnitController;
use App\Http\Controllers\invoice\InvoiceController;
use App\Http\Controllers\invoice\InvoiceItemsController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\VisaPaymentController;
use App\Models\Cheque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::post('/login', [LoginController::class, 'loginUserApi']);

    Route::middleware(['language_middleware'])->group(function () {

        Route::get('/profile', function (Request $request) {
            return  $request->user();
        });
        Route::apiResource('journal', JournalController::class);

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
            Route::get('get-invoice-item-data', [InvoiceItemsController::class, 'invoiceItemPreview']);
            Route::get('invoice/{invoice}/cost', [InvoiceController::class, 'getInvoiceCost']);
            Route::post('invoice/{invoice}/commission', [InvoiceController::class, 'createInvoiceCommission']);
            Route::get('invoice/{invoice}/commission', [InvoiceController::class, 'getInvoiceCommission']);

            Route::get('adjustment-note/create', [AdjustmentNoteController::class, 'create']);
            Route::apiResource('adjustment-note', AdjustmentNoteController::class)->only(['store', 'update', 'show', 'index']);
            Route::get('get-adjustment-note-item-data', [AdjustmentNoteItemController::class, 'previewAdjustmentNoteItem']);
        });

        // Cheques
        Route::prefix('')->group(function () {
            Route::apiResource('cheque', ChequeController::class)->only(['index', 'store', 'show']);
            Route::post('cheque/{id}', [ChequeController::class, 'update']);
            Route::put('deposit-cheque/{id}', [ChequeController::class, 'depositCheque']);
            Route::get('account-cheques/{account}', [ChequeController::class, 'getChequesPerAccount']);
            Route::post('create-multiple-cheques', [ChequeController::class, 'createMultipleCHeques']);
        });

        Route::apiResource('visa-payment', VisaPaymentController::class)->only(['index', 'store', 'show', 'update']);
        Route::put('deposit-visa-payment/{id}', [VisaPaymentController::class, 'depositVisaPayment']);
    });
});
