<?php

use App\Http\Controllers\Api\V1\Account\AccountController;
use App\Http\Controllers\Api\V1\Transaction\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1/')->group(function () {
    Route::controller(AccountController::class)
        ->prefix('accounts/')
        ->group(function () {
            Route::post('store', 'store');
            Route::get('show/{account}', 'show');
        });

    Route::controller(TransactionController::class)
        ->prefix('transactions/')
        ->group(function () {
            Route::post('store', 'store');
            Route::get('history/{account}', 'histories');
        });
});

