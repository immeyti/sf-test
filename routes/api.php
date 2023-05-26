<?php

use App\Http\Controllers\Client\DelayController;
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
    Route::post('/delay-report/{order}', [DelayController::class, 'storeDelayReport']);
});

Route::middleware('auth:sanctum')
    ->post('/assign-delay-report', [DelayController::class, 'delayAssign']);
