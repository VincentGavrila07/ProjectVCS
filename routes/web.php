<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DbConnectController;

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/pelajar', function () {
    return view('mainpage/pelajar');
});
Route::get('/tutor', function () {
    return view('mainpage/tutor');
});

Route::get('/checkdb',  [DbConnectController::class, 'checkConnection']);
Route::get('/login',  [LoginController::class, 'showLogin'])->name('login');
Route::post('/loginlogic',  [LoginController::class, 'login'])->name('loginsubmit');
// routes/web.php
Route::get('/register',  [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register',  [RegisterController::class, 'register'])->name('registersubmit');  // Pastikan ini sesuai dengan form action
