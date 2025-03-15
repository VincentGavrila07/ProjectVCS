<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DbConnectController;
use App\Http\Controllers\FindTutorController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\VideoCallController;


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

Route::get('/profile/pelajar', [ProfileController::class, 'editPelajar'])->name('profile.edit.pelajar');
Route::get('/profile/tutor', [ProfileController::class, 'editTutor'])->name('profile.edit.tutor');

Route::put('/profile/pelajar', [ProfileController::class, 'updatePelajar'])->name('profile.update.pelajar');
Route::put('/profile/tutor', [ProfileController::class, 'updateTutor'])->name('profile.update.tutor');


Route::get('/findTutor', [FindTutorController::class, 'index'])->name('findTutor');
Route::get('/get-tutors', [FindTutorController::class, 'getTutors'])->name('tutors.get');

Route::post('/tutor/toggle-availability', [TutorController::class, 'toggleAvailability']);
Route::get('/get-tutor-status', function () {
    $tutor = \App\Models\MsUser::find(session('id'));
    return response()->json(['isAvailable' => $tutor ? $tutor->isAvailable : false]);
});
Route::post('/tutor/set-afk', [TutorController::class, 'setAFK'])->name('tutor.setAFK');


Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
Route::get('/chat/{room_id}', [ChatController::class, 'showRoom'])->name('chat.room');
Route::post('/chat/{room_id}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
Route::get('/chat/create/{tutor_id}', [ChatController::class, 'createRoom'])->name('chat.create');


Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
Route::match(['get', 'post'], '/wallet/handle-notification', [WalletController::class, 'handleNotification'])->name('wallet.handle.notification');


    Route::post('/sewa-tutor', [TutorController::class, 'sewaTutor'])->name('sewa.tutor');
    Route::post('/confirm-request', [TutorController::class, 'confirmRequest'])->name('confirm.request');
    Route::post('/reject-request', [TutorController::class, 'rejectRequest'])->name('reject.request');
    Route::get('/check-notification', [TutorController::class, 'checkNotification'])->name('check.notification');
    Route::post('/check-transaction-status', [TutorController::class, 'checkTransactionStatus'])->name('check.transaction.status');
    Route::get('/video-call/{transaction_id}', [VideoCallController::class, 'index'])->name('video.call');

