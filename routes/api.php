<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use app\Http\Controllers\api\{
     CityController,
     ZoneController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// City Routes
Route::controller(CityController::class)->group(function () {
    Route::get('city', 'index');
});


// Zone Routes
Route::controller(ZoneController::class)->group(function () {
    Route::get('zone', 'index');
});



//Admin Routes
Route::group(['middleware' => ['auth:sanctum', 'AdminMiddleware']], function () {

    // City Routes
    Route::controller(CityController::class)->group(function () {
        Route::post('city/create', 'create');
        Route::post('city/{id}/update', 'update');
        Route::delete('city/{id}/delete', 'delete');
    });

    // Zone Routes
    Route::controller(ZoneController::class)->group(function () {
        Route::post('zone/create', 'create');
        Route::post('zone/{id}/update', 'update');
        Route::delete('zone/{id}/delete', 'delete');
    });
});
