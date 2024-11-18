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
        if ($item->status === 'sold' || $item->status === 'transaction') {
            return redirect()->back()->with('error', 'この商品は既に購入済みです。');
        }

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

        DB::beginTransaction();

        try {
            $addressData = session('address', [
                'postal_code' => '',
                'address' => '',
                'building' => ''
            ]);
            $shippingAddress = "〒{$addressData['postal_code']} {$addressData['address']} {$addressData['building']}";

            $order = Order::updateOrCreate(
                ['user_id' => Auth::id(), 'item_id' => $item->id],
                [
                    'status' => 'pending',
                    'payment_method' => $paymentMethod,
                    'shipping_address' => $shippingAddress,
                ]
            );

            $item->status = 'transaction';
            $item->save();

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
                'cancel_url' => route('purchase.cancel', ['item' => $item->id]),
                'customer_email' => Auth::user()->email,
                'payment_intent_data' => [
                    'metadata' => [
                        'order_id' => $order->id,
                    ],
                ],
            ]);

            $order->stripe_session_id = $checkoutSession->id;
            $order->save();

            DB::commit();

            Log::info('Stripe Checkoutセッションが作成されました', [
                'session_id' => $checkoutSession->id,
                'order_id' => $order->id,
            ]);

            return redirect($checkoutSession->url);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Stripe決済エラー', [
                'message' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', '決済処理中にエラーが発生しました。');
        }
    }

    public function handleSuccess()
    {
        return redirect()->route('mypage', ['tab' => 'buy'])->with('success', '購入が完了しました。');
    }

    public function handleCancel(Item $item)
    {
        DB::beginTransaction();

        try {
            $order = Order::where('user_id', Auth::id())
                ->where('item_id', $item->id)
                ->where('status', 'pending')
                ->first();

            if ($order) {
                $order->delete();
            }

            $item->status = '在庫あり';
            $item->save();

            DB::commit();

            return redirect()->route('item', ['item' => $item->id]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('キャンセル処理エラー', [
                'message' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);

            return redirect()->route('item', ['item' => $item->id])->with('error', 'キャンセル処理中にエラーが発生しました。');
        }
    }
}
