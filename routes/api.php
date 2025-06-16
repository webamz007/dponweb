<?php

use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MarketController;
use App\Http\Controllers\Api\OTPController;
use App\Http\Controllers\Api\PassbookController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Auth Controller
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Market Controller
Route::post('rates', [MarketController::class, 'rates']);
Route::post('get-market', [MarketController::class, 'getMarket']);

// Setting Controller
Route::post('time', [SettingController::class, 'getTime']);
Route::post('settings', [SettingController::class, 'getSettings']);
Route::post('update-password', [SettingController::class, 'changePassword']);
Route::post('notice-boards', [SettingController::class, 'noticeBoard']);

// Passbook Controller
Route::post('passbook', [PassbookController::class, 'getPassbook']);
Route::post('get-passbook', [PassbookController::class, 'gamePassbookData']);
Route::post('market-results', [PassbookController::class, 'getResults']);

// Transaction Controller
Route::post('bid-history', [TransactionController::class, 'getBidHistory']);
Route::post('transactions', [TransactionController::class, 'getTransaction']);
Route::post('withdraw-request', [TransactionController::class, 'withdrawRequest']);
Route::post('insert-bid', [TransactionController::class, 'insertBid']);
Route::post('add-funds', [TransactionController::class, 'addFunds']);


// User Controller
Route::post('profile', [UserController::class, 'myProfile']);
Route::post('update-profile', [UserController::class, 'updateProfile']);

// Regular user routes for web notifications
Route::get('/notifications', [NotificationController::class, 'fetchNotifications']);
Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead']);

// Routes for verify phone number in register component using 2Factor.in
Route::post('/send-call-otp', [OTPController::class, 'sendCallOTP']);
Route::post('/verify-otp', [OTPController::class, 'verifyOTP']);
