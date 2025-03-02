<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\StripeWebhookController;

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

// 認証が必要なルート
Route::middleware('auth')->group(function () {
    // プロフィール関連
    Route::get('/mypage/profile', [ProfileController::class, 'showProfileForm'])->name('profile');

    Route::patch('/mypage/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');

    Route::get('/mypage', [MypageController::class, 'showMyPage'])->name('mypage');

    // 商品出品関連
    Route::get('/sell', [SellController::class, 'showSellForm'])->name('sell');

    Route::post('/sell', [SellController::class, 'sellItem'])->name('sell.item');

    // いいね機能関連
    Route::post('/item/{item}/like', [LikeController::class, 'like'])->name('like');

    Route::delete('/item/{item}/like', [LikeController::class, 'unlike'])->name('unlike');

    // コメント機能関連
    Route::post('/item/{item}/comment', [CommentController::class, 'comment'])->name('comment');

    // 商品購入関連
    Route::get('/purchase/{item}', [PurchaseController::class, 'showPurchasePage'])->where('item', '[0-9]+')->name('purchase');

    Route::post('/purchase/{item}', [PurchaseController::class, 'purchaseItem'])->where('item', '[0-9]+')->name('purchase.item');

    // 住所変更関連（商品購入時）
    Route::get('/purchase/{item}/address', [AddressController::class, 'showAddressChangePage'])->name('address');

    Route::post('/purchase/{item}/address', [AddressController::class, 'changeAddress'])->name('address.change');

    // stripe決済成功時
    Route::get('/purchase/success/{item}', [PurchaseController::class, 'handleSuccess'])->name('purchase.success');

    // stripe決済キャンセル時
    Route::get('/purchase/cancel/{item}', [PurchaseController::class, 'handleCancel'])->name('purchase.cancel');
});

 // デフォルトのメール認証ルートを上書き
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'setUserFromId']) // 'auth' ミドルウェアは削除、カスタム'setUserFromId'ミドルウェアを追加
    ->name('verification.verify');

// stripe webhookイベント用ルート
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
