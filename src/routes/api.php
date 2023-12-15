<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceOrderController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/service-orders', [ServiceOrderController::class, 'index']);
    Route::post('/service-orders', [ServiceOrderController::class, 'store']);
    Route::put('/service-orders/{id}', [ServiceOrderController::class, 'update']);
    Route::delete('/service-orders/{id}', [ServiceOrderController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
