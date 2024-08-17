<?php

use App\Http\Controllers\HouseController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::apiResource('resident', ResidentController::class)->middleware('auth:sanctum');
Route::apiResource('payment', PaymentController::class)->middleware('auth:sanctum');
Route::apiResource('expenses', ExpensesController::class)->middleware('auth:sanctum');
Route::apiResource('house', HouseController::class)->middleware('auth:sanctum');
Route::post('house/{id}/assign-resident', [HouseController::class, 'assignResident'])->middleware('auth:sanctum');
Route::get('summary', [ReportController::class, 'summary'])->middleware('auth:sanctum');
Route::get('monthly-detail/{month}', [ReportController::class, 'monthlyDetail'])->middleware('auth:sanctum');
