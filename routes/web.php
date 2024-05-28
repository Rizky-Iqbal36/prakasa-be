<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::prefix('movie')->group(function () {
    Route::get('list', [MovieController::class, 'list']);
    Route::post('create', [MovieController::class, 'create']);
    Route::put('update', [MovieController::class, 'update']);
    Route::delete('{movie_id}', [MovieController::class, 'delete']);
});

Route::prefix('watchlist')->group(function () {
    Route::get('', [WatchlistController::class, 'list']);
    Route::post('create', [WatchlistController::class, 'create']);
    Route::put('update', [WatchlistController::class, 'update']);
    Route::delete('{watchlist_id}', [WatchlistController::class, 'delete']);
});
