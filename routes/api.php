<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\Api\UserController;

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

Route::group([
  'prefix' => '/auth',
], function () {
   Route::post('/register', UserController::class . '@register');
   Route::get('/email_resend', UserController::class . '@emailResend')->middleware(['auth:sanctum', 'ability:email-verify']);
   Route::post('/email_verify', UserController::class . '@emailVerify')->middleware(['auth:sanctum', 'ability:email-verify']);
   Route::post('/login', UserController::class . '@login');
   Route::get('/user', UserController::class . '@getUser');
   Route::post('/password_forgot', UserController::class . '@passwordForgot');
   Route::post('/pf_email_verify', UserController::class . '@pf_emailVerify')->middleware(['auth:sanctum', 'ability:email-verify']);
   Route::post('/pf_password_reset', UserController::class . '@pf_passwordReset')->middleware(['auth:sanctum', 'ability:password-reset']);
});
