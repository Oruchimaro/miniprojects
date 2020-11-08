<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\Admin\AdminForgotPasswordController;
use App\Http\Controllers\Auth\Admin\AdminLoginController;
use App\Http\Controllers\Auth\Admin\AdminResetPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [UserController::class, 'index'])->name('user.home');


Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login.form');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login');
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/soft-logout', [AdminController::class, 'softLogout'])->name('admin.soft-logout');
    Route::post('/password/email', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.password.email');
    Route::get('/password/reset', [AdminForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
    Route::post('/password/reset', [AdminResetPasswordController::class, 'reset'])->name('admin.password.reset');
    Route::get('/password/reset/{token}', [AdminResetPasswordController::class, 'showResetForm'])->name('admin.password.update');
});
