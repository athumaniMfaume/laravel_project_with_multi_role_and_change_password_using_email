<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/login', [AuthController::class,'login'])->name('login');
Route::post('/login/post', [AuthController::class,'login_post'])->name('login_post');

Route::get('/register', [AuthController::class,'register'])->name('register');
Route::post('/register/post', [AuthController::class,'register_post'])->name('register_post');

Route::get('/forgot_password', [AuthController::class,'forgot_password'])->name('forgot_password');
Route::post('/forgot_password_post', [AuthController::class,'forgot_password_post'])->name('forgot_password_post');

Route::get('/reset/{token}', [AuthController::class,'reset_password'])->name('reset_password');
Route::post('/reset_password_post', [AuthController::class,'reset_password_post'])->name('reset_password_post');


Route::group(['middleware' => 'superadmin'], function () {
    Route::get('/superadmin/dashboard', [HomeController::class, 'superadmin'])->name('superadmin.dashboard');
    
});

Route::group(['middleware' => 'admin'], function () {
    Route::get('/admin/dashboard', [HomeController::class, 'admin'])->name('admin.dashboard');
});

Route::group(['middleware' => 'user'], function () {
    Route::get('/user/dashboard', [HomeController::class, 'user'])->name('user.dashboard');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/change_password', [AuthController::class, 'change_password'])->name('change_password');
Route::post('/change_password_post', [AuthController::class, 'change_password_post'])->name('change_password_post');


