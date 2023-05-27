<?php

use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Agent\OrderController as AgentOrderController;
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

Route::middleware('auth:sanctum')->group(function() {
    Route::post('clients/delay-report/{order}', [OrderController::class, 'delayReport']);
    Route::post('admin/assign-delay-report', AgentOrderController::class);
});
