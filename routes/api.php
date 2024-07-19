<?php

use App\Http\Controllers\LinkController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->controller(LinkController::class)->prefix('links')->group(function () {
    Route::post('', 'make');
    Route::get('max-clicks', 'getMaxClicks');
    Route::get('', 'get');
    Route::get('{hash_link}', 'getLink');
});
