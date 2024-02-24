<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionApiController;
use App\Http\Controllers\HomeController;

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
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});
Route::controller(TransactionApiController::class)->middleware('auth')->group(function () {
   Route::post('/deposit', 'deposit');
    Route::post('/withdraw', 'withdraw');
    Route::post('/transfer', 'transfer');
    Route::get('/statement', 'statement');
});

Route::controller(HomeController::class)->middleware('auth')->group(function () {
    Route::get('/home', 'index');
});
