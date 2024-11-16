<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Http\Requests\PurchaseRequest;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    public function showPurchasePage(Request $request, Item $item)
    {
        $profile = Profile::where('user_id', Auth::id())->first();
        $address = $request->session()->get('address', null);
        $payment_method = $request->session()->get('payment_method', null);

        if (is_null($profile) || empty($profile->postal_code) || empty($profile->address)) {
            return redirect()->route('profile')->with('alert', '購入手続きを行う前に、プロフィールで住所を設定してください。');
        }

        return view('purchase', compact('item', 'profile', 'address', 'payment_method'));
    }

    public function purchaseItem(PurchaseRequest $request, Item $item)
    {
        // 商品が既に購入されていないか確認
        if ($item->status === 'sold') {
            return redirect()->back()->with('error', 'この商品は既に購入済みです。');
        }

        // セッションに支払い方法を保存
        session(['payment_method' => $request->input('payment_method')]);

        // トランザクションの開始
        DB::beginTransaction();

        try {
            $shipping_address = '〒' . $request->input('postal_code') . ' ' . $request->input('address') . ' ' . $request->input('building');

            // オーダーの作成
            $order = new Order();
            $order->user_id = Auth::id();
            $order->item_id = $item->id;
            $order->payment_method = $request->input('payment_method');
            $order->shipping_address = $shipping_address;
            $order->save();

            // 商品のステータスを'sold'に更新
            $item->status = 'sold';
            $item->save();

            // トランザクションのコミット
            DB::commit();

            // 購入完了後、マイページへリダイレクト
            return redirect()->route('mypage', ['tab' => 'buy'])->with('success', '購入が完了しました。');
        } catch (\Exception $e) {
            // トランザクションのロールバック
            DB::rollback();

            // エラーログの記録
            Log::error($e);

            // エラーメッセージと共に元のページへリダイレクト
            return redirect()->back()->with('error', '購入処理中にエラーが発生しました。');
        }
    }
}
