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
use App\Http\Middleware\CheckSession;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('landing');

// ------------------------------------------- Auth Routes -------------------------------------------
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/loginlogic', [LoginController::class, 'login'])->name('loginsubmit');
Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('registersubmit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ------------------------------------------- Protected Routes -------------------------------------------
Route::middleware(['check.session'])->group(function () {
    
    // ------------------------------------------- Admin Routes -------------------------------------------
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
    Route::get('/admin/userList', [AdminController::class, 'userList'])->name('userList');
    Route::get('/admin/tutorList', [AdminController::class, 'tutorList'])->name('tutorList');
    Route::delete('/admin/tutor/{id}', [AdminController::class, 'deleteTutor'])->name('deleteTutor');
    Route::get('/admin/pelajarList', [AdminController::class, 'pelajarList'])->name('pelajarList');
    Route::delete('/admin/pelajar/{id}', [AdminController::class, 'deletePelajar'])->name('deletePelajar');
    Route::get('/admin/transaksiList', [AdminController::class, 'transaksiList'])->name('transaksiList');
    Route::delete('/admin/transactions/{id}', [AdminController::class, 'destroyTransaction'])->name('transactions.destroy');
    Route::get('/admin/subjectList', [AdminController::class, 'subjectList'])->name('subjectList');
    Route::post('/subject/store', [AdminController::class, 'store'])->name('subject.store');
    Route::put('/subject/update/{id}', [AdminController::class, 'update'])->name('subject.update');
    Route::delete('/admin/subject/{id}', [AdminController::class, 'destroySubject'])->name('subject.destroy');
    Route::get('/admin/withdrawList', [AdminController::class, 'withdrawList'])->name('withdrawList');
    // Route untuk membuka halaman edit withdraw
    Route::get('/withdraw/edit/{id}', [WalletController::class, 'editWithdraw'])->name('withdraw.edit');
    Route::put('/withdraw/update/{id}', [WalletController::class, 'updateWithdrawStatus'])->name('withdraw.update');


    // ------------------------------------------- User Routes -------------------------------------------
    Route::get('/pelajar', function () {
        return view('mainpage/pelajar/index');
    })->name('pelajar');
    Route::get('/tutor', function () {
        return view('mainpage/tutor/index');
    })->name('tutor');
    
    // ------------------------------------------- Profile Routes -------------------------------------------
    Route::get('/profile/pelajar', [ProfileController::class, 'editPelajar'])->name('profile.edit.pelajar');
    Route::get('/profile/tutor', [ProfileController::class, 'editTutor'])->name('profile.edit.tutor');
    Route::put('/profile/pelajar', [ProfileController::class, 'updatePelajar'])->name('profile.update.pelajar');
    Route::put('/profile/tutor', [ProfileController::class, 'updateTutor'])->name('profile.update.tutor');
    
    // ------------------------------------------- Tutor Management -------------------------------------------
    Route::get('/findTutor', [FindTutorController::class, 'index'])->name('findTutor');
    Route::get('/get-tutors', [FindTutorController::class, 'getTutors'])->name('tutors.get');
    Route::post('/tutor/toggle-availability', [TutorController::class, 'toggleAvailability']);
    Route::get('/get-tutor-status', function () {
        $tutor = \App\Models\MsUser::find(session('id'));
        return response()->json(['isAvailable' => $tutor ? $tutor->isAvailable : false]);
    });
    Route::post('/tutor/set-afk', [TutorController::class, 'setAFK'])->name('tutor.setAFK');
    Route::post('/sewa-tutor', [TutorController::class, 'sewaTutor'])->name('sewa.tutor');
    Route::post('/confirm-request', [TutorController::class, 'confirmRequest'])->name('confirm.request');
    Route::post('/reject-request', [TutorController::class, 'rejectRequest'])->name('reject.request');
    Route::get('/check-notification', [TutorController::class, 'checkNotification'])->name('check.notification');
    Route::post('/check-transaction-status', [TutorController::class, 'checkTransactionStatus'])->name('check.transaction.status');
    
    // ------------------------------------------- Chat Routes -------------------------------------------
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{room_id}', [ChatController::class, 'showRoom'])->name('chat.room');
    Route::post('/chat/{room_id}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/create/{tutor_id}', [ChatController::class, 'createRoom'])->name('chat.create');
    
    // ------------------------------------------- Wallet Routes -------------------------------------------
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet-tutor', [WalletController::class, 'walletTutor'])->name('tutor.wallet');
    Route::get('/wallet-pelajar', [WalletController::class, 'walletPelajar'])->name('pelajar.wallet');
    Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::post('/wallet-tutor/withdraw', [WalletController::class, 'processWithdrawTutor'])->name('tutor.withdraw.process');
    Route::post('/wallet-pelajar/withdraw', [WalletController::class, 'processWithdrawPelajar'])->name('pelajar.withdraw.process');
    
    
    // ------------------------------------------- Video Call Routes -------------------------------------------
    Route::get('/video-call/{transaction_id}', [VideoCallController::class, 'index'])->name('video.call');
});
Route::match(['get', 'post'], '/wallet/handle-notification', [WalletController::class, 'handleNotification'])->name('wallet.handle.notification');

// ------------------------------------------- Database Connection Check -------------------------------------------
Route::get('/checkdb', [DbConnectController::class, 'checkConnection']);
