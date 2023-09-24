<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function() {
  return 'success';
})->middleware('auth:admin');

Route::group([
  'prefix' => '/auth',
], function () {
  Route::get('/', AdminController::class . '@auth')->name('admin.auth')->middleware('guest:admin');
  Route::post('/register', AdminController::class . '@register')->middleware('guest:admin');
  Route::get('/email_resend', AdminController::class . '@emailResend')->middleware(['auth:sanctum', 'ability:email-verify']);
  Route::post('/email_verify', AdminController::class . '@emailVerify')->middleware(['guest:admin', 'auth:sanctum', 'ability:email-verify']);
  Route::post('/login', AdminController::class . '@login')->middleware('guest:admin');
  // Route::post('/password_forgot', AdminController::class . '@passwordForgot');
  // Route::post('/pf_email_verify', AdminController::class . '@pf_emailVerify')->middleware(['auth:sanctum', 'ability:email-verify']);
  // Route::post('/pf_password_reset', AdminController::class . '@pf_passwordRest')->middleware(['auth:sanctum', 'ability:password-reset']);
});

Route::get('/logout', AdminController::class . '@logout');
