<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Item;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);

            Log::info('Webhook received', ['type' => $event->type]);

            switch ($event->type) {
                case 'checkout.session.completed':
                case 'checkout.session.async_payment_succeeded':
                    $session = $event->data->object;

                    Log::info('Checkout session completed', ['session_id' => $session->id]);

                    $order = Order::where('stripe_session_id', $session->id)->first();

                    if ($order) {
                        $order->status = 'paid';
                        $order->save();

                        $item = Item::find($order->item_id);
                        if ($item) {
                            $item->status = 'sold';
                            $item->save();
                        }

                        Log::info('Order status updated to paid.', ['order_id' => $order->id]);
                    } else {
                        Log::error('Order not found for session', ['session_id' => $session->id]);
                    }
                    break;

                default:
                    Log::info('Unhandled event type', ['type' => $event->type]);
                    break;
            }
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid payload', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Invalid signature', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        return response()->json(['status' => 'success'], 200);
    }
}