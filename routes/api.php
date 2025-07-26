<?php

use App\Http\Controllers\API\PaymentController;
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

// Payment Gateway Routes
Route::middleware('auth:sanctum')->post('/payment/token', [PaymentController::class, 'getSnapToken']);
Route::post('/payment/notification', [PaymentController::class, 'handleNotification']);
Route::get('/payment/success', [PaymentController::class, 'handleSuccess'])->name('payment.success');
Route::get('/payment/pending', [PaymentController::class, 'handlePending'])->name('payment.pending');
Route::get('/payment/error', [PaymentController::class, 'handleError'])->name('payment.error'); 