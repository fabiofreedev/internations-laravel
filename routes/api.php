<?php

use App\Domain\Auth\Controllers\AuthController;
use App\Domain\Users\Controllers\UserController;
use App\Domain\Users\Groups\Controllers\GroupController;
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

Route::middleware('admin')->group(function () {
    Route::apiResource('users', UserController::class)->only(['index', 'store', 'destroy']);
    Route::prefix('users')->group(function () {
        Route::apiResource('groups', GroupController::class)->only(['index', 'store', 'destroy']);
        Route::put('groups/{group}/user', [GroupController::class, 'addUser']);
        Route::delete('groups/{group}/user/{user}', [GroupController::class, 'removeUser']);
    });
});

Route::post('/login', [AuthController::class, 'login']);
