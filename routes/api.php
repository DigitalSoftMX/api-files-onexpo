<?php

use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\docController;
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

Route::post('add/base64/{id}', [FileController::class, 'addBase64']);

Route::post('getimage/affiliate', [FileController::class, 'getImageAffiliate']);
Route::post('updateimage/affiliate/{id}',[FileController::class, 'updateImageAffiliate']);

Route::post('addimage/supplier/{id}/{product?}', [FileController::class, 'addImageSupplier']);
Route::post('getimage/supplier', [FileController::class, 'getImageSupplier']);
Route::post('updateimage/supplier/{id}/{product?}', [FileController::class, 'updateImageSupplier']);

/* Docs carpetas pdf y xml */
Route::post('doc/get/{id}', [docController::class, 'get']);
Route::post('doc/all/{id}', [docController::class, 'get']);
Route::post('doc/add/{id}', [docController::class, 'add']);
Route::post('doc/update/{id}', [docController::class, 'update']);
Route::post('doc/delete/{id}', [docController::class, 'delete']);
