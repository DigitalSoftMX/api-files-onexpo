<?php

use App\Http\Controllers\Api\FileController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('addimage/affiliate/{id}', [FileController::class, 'addImageAffiliate']);
Route::post('getimage/affiliate', [FileController::class, 'getImageAffiliate']);

Route::post('addimage/supplier/{id}/{product?}', [FileController::class, 'addImageSupplier']);
