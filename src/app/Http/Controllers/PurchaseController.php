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
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

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

        // 配列形式でセッションに保存
        $addressData = [
            'postal_code' => $request->input('postal_code'),
            'address' => $request->input('address'),
            'building' => $request->input('building'),
        ];

        $paymentMethod = $request->input('payment_method');

        $request->session()->put('address', $addressData);
        $request->session()->put('payment_method', $paymentMethod);

        return $this->redirectToStripe($item, $paymentMethod);
    }

    private function redirectToStripe(Item $item, $paymentMethod)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        try {
			$lineItem = [
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => intval(round($item->price)),
                ],
                'quantity' => 1,
            ];

            $paymentMethodTypes = $paymentMethod === 'カード払い' ? ['card'] : ['konbini'];

            $checkoutSession = StripeSession::create([
                'payment_method_types' => $paymentMethodTypes,
                'line_items' => [$lineItem],
                'mode' => 'payment',
                'success_url' => route('purchase.success', ['item' => $item->id]),
                'cancel_url' => route('purchase', ['item' => $item->id]) . '?error=cancelled',
                'customer_email' => Auth::user()->email, // コンビニ払いの場合は必須
                'payment_method_options' => [
                    'konbini' => [
                        'expires_after_days' => 3, // 支払い期限を3日後に設定
                    ],
                ],
            ]);

            Log::info('Stripe Checkoutセッションが作成されました', [
                'session_id' => $checkoutSession->id,
                'item_id' => $item->id,
                'user_id' => Auth::id(),
            ]);

            return redirect($checkoutSession->url);
        } catch (\Exception $e) {
            Log::error('Stripe決済エラー', [
                'message' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', '決済処理中にエラーが発生しました。');
        }
    }

    public function handleSuccess(Request $request, Item $item)
    {
		DB::beginTransaction();

        try {
            $addressData = $request->session()->get('address');
            $postalCode = $addressData['postal_code'] ?? '';
            $address = $addressData['address'] ?? '';
            $building = $addressData['building'] ?? '';

            $shippingAddress = "〒{$postalCode} {$address} {$building}";

            $paymentMethod = $request->session()->get('payment_method');

            $order = new Order();
            $order->user_id = Auth::id();
            $order->item_id = $item->id;
            $order->payment_method = $paymentMethod;
            $order->shipping_address = $shippingAddress;
            $order->save();

            $item->status = 'sold';
            $item->save();

            DB::commit();

            return redirect()->route('mypage', ['tab' => 'buy'])->with('success', '購入が完了しました。');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('購入処理エラー', [
                'message' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);

            return redirect()->route('purchase', ['item' => $item->id])->with('error', '購入処理中にエラーが発生しました。');
        }
    }

    // public function purchaseItem(PurchaseRequest $request, Item $item)
    // {
    //     // 商品が既に購入されていないか確認
    //     if ($item->status === 'sold') {
    //         return redirect()->back()->with('error', 'この商品は既に購入済みです。');
    //     }

    //     // セッションに支払い方法を保存
    //     session(['payment_method' => $request->input('payment_method')]);

    //     // トランザクションの開始
    //     DB::beginTransaction();

    //     try {
    //         $shipping_address = '〒' . $request->input('postal_code') . ' ' . $request->input('address') . ' ' . $request->input('building');

    //         // オーダーの作成
    //         $order = new Order();
    //         $order->user_id = Auth::id();
    //         $order->item_id = $item->id;
    //         $order->payment_method = $request->input('payment_method');
    //         $order->shipping_address = $shipping_address;
    //         $order->save();

    //         // 商品のステータスを'sold'に更新
    //         $item->status = 'sold';
    //         $item->save();

    //         // トランザクションのコミット
    //         DB::commit();

    //         // 購入完了後、マイページへリダイレクト
    //         return redirect()->route('mypage', ['tab' => 'buy'])->with('success', '購入が完了しました。');
    //     } catch (\Exception $e) {
    //         // トランザクションのロールバック
    //         DB::rollback();

    //         // エラーログの記録
    //         Log::error($e);

    //         // エラーメッセージと共に元のページへリダイレクト
    //         return redirect()->back()->with('error', '購入処理中にエラーが発生しました。');
    //     }
    // }
}
