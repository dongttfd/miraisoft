<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/accounts', 'AccountController@index');
Route::get('/accounts/{register_id}', 'AccountController@show');
Route::post('/accounts', 'AccountController@store');
Route::put('/accounts/{register_id}', 'AccountController@update');
Route::delete('/accounts/{register_id}', 'AccountController@destroy');

Route::post('/showSerialpaso', 'FileController@show');
