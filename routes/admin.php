<?php

use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\MarketController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('admin')->group(function (){
    Route::get('/', [UserController::class, 'adminDashboard'])->name('dashboard');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('star-users', [UserController::class, 'starPlayers'])->name('users.star');
    Route::get('mark-star', [UserController::class, 'markStar']);
    Route::get('block-user', [UserController::class, 'blockUser'])->name('user.block');
    Route::get('withdraw-deposit-points', [UserController::class, 'depositWithdrawPoint'])->name('user.points');
    Route::get('withdraw-deposit-update', [UserController::class, 'depositWithdrawTransaction'])->name('user.points.update');
    Route::get('transactions', [TransactionController::class, 'transactions'])->name('user.transactions');
    Route::get('winners-list', [UserController::class, 'winnersList'])->name('user.winners');
    Route::get('destroy', [UserController::class, 'destroy']);
    Route::get('withdraw-status', [UserController::class, 'withdrawStatus'])->name('withdraw.status');


    Route::get('passbook', [UserController::class, 'passbook'])->name('users.passbook');
    Route::get('bids', [UserController::class, 'userBids'])->name('users.bids');
    Route::get('bids-all', [UserController::class, 'bidsManagement'])->name('bids.all');
    Route::get('refer-users', [UserController::class, 'referUsers'])->name('users.refer');
    Route::get('agents', [AgentController::class, 'index'])->name('agents.index');
    Route::get('mark-agent', [AgentController::class, 'markAgent']);

// Market Routes
    Route::get('markets', [MarketController::class, 'index'])->name('markets');
    Route::get('starline-markets', [MarketController::class, 'starlineMarkets'])->name('markets.starline');
    Route::get('starline-settings', [MarketController::class, 'starlineSettings'])->name('starline.settings');
    Route::get('delhi-markets', [MarketController::class, 'delhiMarkets'])->name('markets.delhi');
    Route::get('delhi-settings', [MarketController::class, 'delhiSettings'])->name('delhi.settings');
    Route::get('market-type-store', [MarketController::class, 'createNewTypeMarket'])->name('market.type.store');
    Route::get('market-store', [MarketController::class, 'store'])->name('market.store');
    Route::get('market-edit', [MarketController::class, 'edit'])->name('market.edit');
    Route::get('market-update', [MarketController::class, 'update'])->name('market.update');
    Route::get('market-delete', [MarketController::class, 'destroy'])->name('market.destroy');
    Route::get('game-settings', [MarketController::class, 'gameSettings'])->name('game.settings');
    Route::get('game-settings-edit', [MarketController::class, 'gameSettingsEdit'])->name('game.settings.edit');
    Route::get('game-settings-update', [MarketController::class, 'gameSettingsUpdate'])->name('game.settings.update');
    Route::get('markets-status', [MarketController::class, 'marketsStatus'])->name('markets.status');
    Route::post('sort-market', [MarketController::class, 'sortMarket'])->name('drag.market');


// Results Routes
    Route::get('results-create', [ResultController::class, 'create'])->name('result.create');
    Route::get('results-store', [ResultController::class, 'storeResult'])->name('result.store');
    Route::get('results', [ResultController::class, 'index'])->name('results');
    Route::get('result-winners', [ResultController::class, 'winners'])->name('result.winners');
    Route::get('results-delete', [ResultController::class, 'resultDelete'])->name('result.delete');
    Route::get('results-reverse', [ResultController::class, 'reverseResult'])->name('result.reverse');
    Route::get('create-delhi-result', [ResultController::class, 'createDelhiResult'])->name('result.delhi.create');
    Route::get('store-delhi-result', [ResultController::class, 'storeDelhiResult'])->name('result.delhi.store');
    Route::get('list-delhi-result', [ResultController::class, 'listDelhiResults'])->name('result.delhi.list');
    Route::get('starline-results', [ResultController::class, 'starlineResults'])->name('star.results');
    Route::get('pay-to-all', [ResultController::class, 'payToAll'])->name('pay.all');

// Reports Routes
    Route::get('reports', [ReportController::class, 'generalReport'])->name('report.general');
    Route::get('report-types', [ReportController::class, 'typeReport'])->name('report.types');
    Route::get('withdraw-report', [ReportController::class, 'withdrawReport'])->name('report.withdraw');
    Route::get('withdraw-report-complete', [ReportController::class, 'completeWithdraw'])->name('report.withdraw.complete');
    Route::get('deposit-report', [ReportController::class, 'depositReport'])->name('report.deposit');
    Route::get('export-withdraw-report', [ReportController::class, 'exportWithdrawReport'])->name('report.withdraw.export');


// Setting Route
    Route::get('ratio-setting', [SettingController::class, 'ratioSetting'])->name('setting.ratio');
    Route::get('ratio-setting-update', [SettingController::class, 'ratioSettingUpdate'])->name('setting.ratio.update');
    Route::get('starline-setting', [SettingController::class, 'starSetting'])->name('setting.star');
    Route::get('star-setting-update', [SettingController::class, 'starSettingUpdate'])->name('setting.star.update');
    Route::get('delhi-setting', [SettingController::class, 'delhiSetting'])->name('setting.delhi');
    Route::get('delhi-setting-update', [SettingController::class, 'delhiSettingUpdate'])->name('setting.delhi.update');
    Route::get('other-setting', [SettingController::class, 'otherSetting'])->name('setting.other');
    Route::get('other-setting-update', [SettingController::class, 'otherSettingUpdate'])->name('setting.other.update');
    Route::get('slider-setting/{type}', [SettingController::class, 'sliderSetting'])->name('setting.slider')->where('type', 'other|starline|delhi');
    Route::post('upload-slides', [SettingController::class, 'storeSlides'])->name('store.slides');
    Route::delete('/admin/slides/{type}/{id}', [SettingController::class, 'destroySlides'])->name('slides.destroy');
    Route::get('withdrawal-settings', [SettingController::class, 'withdrawalSettings'])->name('withdrawal-settings');
    Route::post('update-withdrawal-settings', [SettingController::class, 'updateWithdrawalSettings'])->name('update-withdrawal-settings');
    Route::get('notice-board/{type}', [SettingController::class, 'noticeBoard'])->name('notice-board')->where('type', 'other|starline|delhi');
    Route::post('update-notice-board', [SettingController::class, 'updateNoticeBoard'])->name('update-notice-board');
});

// Auth Routes
Route::get('login', [AuthController::class, 'create'])->name('admin.login');
Route::post('login', [AuthController::class, 'store'])->name('admin.login.store');
Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');
