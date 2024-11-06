<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;

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
Route::get('/', function () {
    return view('index');
})->name('top');

// 商品詳細ページ
Route::get('/item', function () {
    return view('item');
})->name('item');

// 認証関連のルート
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
    Route::get('/mypage/profile', function () {
        return view('profile');
    })->name('profile');
});