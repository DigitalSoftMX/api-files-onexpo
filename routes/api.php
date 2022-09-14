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

Route::post('add/image/{id}', [FileController::class, 'addImage']);
Route::post('get/image/user', [FileController::class, 'getImageUser']);
Route::post('update/image/user/{id}',[FileController::class, 'updateImageUser']);
/* Imagen del producto solo Proveedor (Supplier) */
Route::post('add/image/product/{id}/{product?}', [FileController::class, 'addImageProduct']);
Route::post('get/image/product', [FileController::class, 'getImageProduct']);
Route::post('update/image/product/{id}/{product?}', [FileController::class, 'updateImageProduct']);

/* Docs carpetas pdf y xml */
Route::post('doc/get/{id}', [docController::class, 'get']);
Route::post('doc/all/{id}', [docController::class, 'all']);
Route::post('doc/add/{id}', [docController::class, 'add']);
Route::post('doc/update/{id}', [docController::class, 'update']);
Route::post('doc/delete/{id}', [docController::class, 'delete']);
