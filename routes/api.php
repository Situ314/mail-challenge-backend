<?php

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

Route::post('register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('login', [\App\Http\Controllers\API\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('users/{id}', [\App\Http\Controllers\Api\UserController::class, 'show']);

    Route::post('send-email', [\App\Http\Controllers\EmailController::class, 'send_email']);
    Route::get('get-emails', [\App\Http\Controllers\EmailController::class, 'get_emails']);
    Route::post('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);

});
