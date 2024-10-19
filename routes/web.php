<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

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



Route::get('/login', [AuthController::class, 'loginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit')->middleware('guest');

Route::get('/redirect-social/{service}', [AuthController::class,'redirectSocial'])->name('social.login');
Route::get('/auth/callback/{service}', [AuthController::class,'callbackSocial']);

Route::middleware('auth')->group(callback: function() {
    Route::get('/logout', [AuthController::class,'logout'])->name('logout');
    Route::get('/', function () {
        return view('admin.pages.dashboard');
    })->name('dashboard');


    Route::resource('category', CategoryController::class);
    Route::resource('product', ProductController::class);
    Route::resource('brand', BrandController::class);

    Route::post('/upload/image', [MediaController::class, 'uploadImage'])->name('upload.image');
});