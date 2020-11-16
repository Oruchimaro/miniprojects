<?php

use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;

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

Route::get('/locale/{locale}', LanguageController::class);


Route::get('/post', function () {
    //add a single post to database post table manually and retrive it here
    $post = \DB::table('post')->first();
    return view('post')->with('post', $post);
});
