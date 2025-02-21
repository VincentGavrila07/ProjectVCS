<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DbConnectController;
use App\Http\Controllers\FindTutorController;
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

Route::get('/admin', [AdminController::class, 'index'])->name('admin'); // Menggunakan controller dan methodnya

// -------------------------------------------Menu Admin-----------------------------------------------------------
Route::get('/admin/userList', [AdminController::class, 'userList'])->name('userList');

Route::get('/admin/tutorList', [AdminController::class, 'tutorList'])->name('tutorList');
Route::delete('/admin/tutor/{id}', [AdminController::class, 'deleteTutor'])->name('deleteTutor');


Route::get('/admin/pelajarList', [AdminController::class, 'pelajarList'])->name('pelajarList');
Route::delete('/admin/pelajar/{id}', [AdminController::class, 'deletePelajar'])->name('deletePelajar');

// -------------------------------------------Menu Admin-----------------------------------------------------------

Route::get('/pelajar', function () {
    return view('mainpage/pelajar/index');
})->name('pelajar');
Route::get('/tutor', function () {
    return view('mainpage/tutor/index');
})->name('tutor');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');




// Route::get('/tutor', [TutorController::class, 'index'])->name('tutor');
Route::get('/checkdb',  [DbConnectController::class, 'checkConnection']);
Route::get('/login',  [LoginController::class, 'showLogin'])->name('login');
Route::post('/loginlogic',  [LoginController::class, 'login'])->name('loginsubmit');

Route::get('/register',  [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register',  [RegisterController::class, 'register'])->name('registersubmit'); 


Route::get('/profile/pelajar', [ProfileController::class, 'edit'])->name('profile.edit.pelajar');
Route::get('/profile/tutor', [ProfileController::class, 'edit'])->name('profile.edit.tutor');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

Route::get('/findTutor', [FindTutorController::class, 'index'])->name('findTutor');