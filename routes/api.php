<?php

use App\Http\Controllers\Api\AccessTokensController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\OtpController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::apiResource('categories', 'Api\CategoriesController');
//Route::apiResource('products', 'Api\ProductsController');
Route::apiResources([
    'categories' => 'Api\CategoriesController',
    'products' => 'Api\ProductsController',
]);

Route::post('access/tokens', [AccessTokensController::class, 'store']);
Route::delete('access/tokens/{token?}', [AccessTokensController::class, 'destroy'])
    ->middleware('auth:sanctum');

Route::post('otp/create', [OtpController::class, 'store'])
    ->middleware('guest:sanctum');
Route::post('otp/verify', [OtpController::class, 'verify'])
    ->middleware('guest:sanctum');