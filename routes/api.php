<?php

use App\Domain\Auth\Controllers\AuthController;
use App\Domain\Users\Controllers\UserController;
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

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('users', UserController::class)->only(['index', 'store', 'destroy']);
});

Route::post('/login', [AuthController::class, 'login']);
