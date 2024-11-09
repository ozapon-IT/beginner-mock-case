<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;


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

// トップページ
Route::get('/', [IndexController::class, 'index'])->name('top');

// 商品詳細ページ
Route::get('/item/{item}', [ItemController::class, 'showItemPage'])->name('item');

// ログイン
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// ログアウト
Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth')->name('logout');

// 登録
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// 認証が必要なルート
Route::middleware('auth')->group(function () {
    // プロフィール関連
    Route::get('/mypage/profile', [ProfileController::class, 'showProfileForm'])->name('profile');
    Route::patch('/mypage/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('/mypage', [MypageController::class, 'showMyPage'])->name('mypage');

    // 商品出品関連
    Route::get('/sell', [SellController::class, 'showSellForm'])->name('sell');
    Route::post('/sell', [SellController::class, 'store'])->name('sell.store');

    // いいね機能関連
    Route::post('/item/{item}/like', [LikeController::class, 'like'])->name('like');
    Route::delete('/item/{item}/like', [LikeController::class, 'unlike'])->name('unlike');

    // コメント機能関連
    Route::post('/item/{item}/comment', [CommentController::class, 'storeComment'])->name('comment');
});