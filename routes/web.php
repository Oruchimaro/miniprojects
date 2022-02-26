<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;


Route::get('/', [VideoController::class, 'index'])->name('home');
Route::get('/uploader', [VideoController::class, 'uploader'])->name('uploader');
Route::post('/upload', [VideoController::class, 'store'])->name('upload');
