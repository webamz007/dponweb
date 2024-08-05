<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Website\GameController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\PaymentController;
use App\Http\Controllers\Website\ProfileController;
use App\Http\Controllers\Website\TransactionController;
use Illuminate\Support\Facades\Artisan;
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

Route::middleware('checkBlocked')->group( function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('panel-chart', [GameController::class, 'panelChart'])->name('chart.panel');
    Route::get('how-to-play', [GameController::class, 'howToPlay'])->name('game.info');
    Route::get('starline-games', [GameController::class, 'showStarlineGames'])->name('game.starline');
    Route::get('delhi-games', [GameController::class, 'showDelhiGames'])->name('game.delhi');
    Route::post('check-register', [RegisteredUserController::class, 'checkRegisterData'])->name('check-register-data');
});

Route::middleware(['auth', 'checkBlocked'])->group(function () {
    Route::get('game', [GameController::class, 'view'])->name('game');
    Route::post('user-points', [GameController::class, 'userPoints'])->name('user-points');
    Route::post('check-market', [GameController::class, 'checkMarket'])->name('check-market');
    Route::post('insert-bid', [\App\Http\Controllers\Api\TransactionController::class, 'insertBid'])->name('insert.bid.game');
    Route::get('game-transaction', [TransactionController::class, 'gameTransaction'])->name('game.transaction');
    Route::get('game-transaction-data', [TransactionController::class, 'gameTransactionData'])->name('game.transaction.data');
    Route::get('history', [TransactionController::class, 'gameHistory'])->name('game.history');
    Route::get('game-history-data', [TransactionController::class, 'gameHistoryData'])->name('game.history.data');
    Route::get('game-passbook', [TransactionController::class, 'gamePassbook'])->name('game.passbook');
    Route::get('game-passbook-data', [TransactionController::class, 'gamePassbookData'])->name('game.passbook.data');
    Route::any('add-funds', [TransactionController::class, 'addFunds'])->name('add.funds');
    Route::any('withdraw-funds', [TransactionController::class, 'withdrawFunds'])->name('withdraw-funds');
    Route::post('withdraw-request', [TransactionController::class, 'withdrawRequest'])->name('user.withdraw.request');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// PhonePe Payment Method Route
Route::get('phonepe',[PaymentController::class,'phonePe'])->name('phonePe');
Route::any('phonepe-response',[PaymentController::class,'response'])->name('response');

// PayU Payment Method Route
Route::post('payUMoney',[PaymentController::class,'payUMoney']);
Route::any('payu-response',[PaymentController::class,'payUResponse'])->name('pay.u.response');
Route::any('pay-u-cancel',[PaymentController::class,'payUCancel'])->name('pay.u.cancel');

// RazorPay Payment Method Route
Route::post('/create-order', [PaymentController::class, 'createOrder']);
Route::post('/verify-payment', [PaymentController::class, 'verifyPayment']);

Route::get('/download-apk', [PaymentController::class,'download']);


Route::get('/create-storage-link', function () {
    Artisan::call('storage:link');
    Artisan::call('cache:clear');
    return 'Storage link created!';
});

require __DIR__.'/auth.php';



