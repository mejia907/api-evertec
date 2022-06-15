<?php

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

Route::get('/orders', 'App\Http\Controllers\OrderController@index'); // trae todas las ordenes
Route::get('/orders/{id}', 'App\Http\Controllers\OrderController@orderForIdApi'); // trae una orden
Route::get('/orders/db/{id}', 'App\Http\Controllers\OrderController@orderForId'); // trae una orden de la DB
Route::post('/orders', 'App\Http\Controllers\OrderController@store'); // crea una nueva orden
Route::put('/orders/{id}', 'App\Http\Controllers\OrderController@update'); // actualiza una orden
Route::put('/orders/update-status/{id}', 'App\Http\Controllers\OrderController@updateStatus'); // actualiza el estado una orden
Route::delete('/orders/{id}', 'App\Http\Controllers\OrderController@destroy'); // elimina una orden