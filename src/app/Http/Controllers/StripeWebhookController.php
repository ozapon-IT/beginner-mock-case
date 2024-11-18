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
                    $this->handleCheckoutSessionCompleted($event);
                    break;

                case 'payment_intent.succeeded':
                    $this->handlePaymentIntentSucceeded($event);
                    break;

                case 'checkout.session.async_payment_failed':
                    $this->handleAsyncPaymentFailed($event);
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

    private function handleCheckoutSessionCompleted($event)
    {
        $session = $event->data->object;

        Log::info('Checkout session completed', ['session_id' => $session->id]);

        $order = Order::where('stripe_session_id', $session->id)->first();

        if ($order) {
            if ($order->status !== 'paid') {
                if ($session->payment_status === 'paid') {
                    $order->status = 'paid';
                    $order->save();

                    $item = Item::find($order->item_id);
                    if ($item && $item->status !== 'sold') {
                        $item->status = 'sold';
                        $item->save();
                    }

                    Log::info('Order status updated to paid in checkout.session.completed.', ['order_id' => $order->id]);
                } else {
                    $order->status = 'pending';
                    $order->save();

                    $item = Item::find($order->item_id);
                    if ($item && $item->status !== 'transaction') {
                        $item->status = 'transaction';
                        $item->save();
                    }

                    Log::info('Order status set to pending in checkout.session.completed.', ['order_id' => $order->id]);
                }
            } else {
                Log::info('Order already marked as paid. No action needed in checkout.session.completed.', ['order_id' => $order->id]);
            }
        } else {
            Log::error('Order not found for session in checkout.session.completed', ['session_id' => $session->id]);
        }
    }

    private function handlePaymentIntentSucceeded($event)
    {
        $paymentIntent = $event->data->object;

        Log::info('PaymentIntent succeeded', ['payment_intent_id' => $paymentIntent->id]);

        $order_id = $paymentIntent->metadata->order_id ?? null;

        if ($order_id) {
            $order = Order::find($order_id);

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
            Log::error('Order not found with order_id from PaymentIntent', ['payment_intent_id' => $paymentIntent->id]);
            }
        }  else {
            Log::error('No order_id in PaymentIntent metadata', ['payment_intent_id' => $paymentIntent->id]);
        }
    }

    private function handleAsyncPaymentFailed($event)
    {
        $session = $event->data->object;

        Log::info('Checkout session async payment failed', ['session_id' => $session->id]);

        $order = Order::where('stripe_session_id', $session->id)->first();

        if ($order) {
            $item = Item::find($order->item_id);

            $order->delete();

            if ($item) {
                $item->status = '在庫あり';
                $item->save();
            }

            Log::info('Order status updated to failed.', ['order_id' => $order->id]);
        } else {
            Log::error('Order not found for session', ['session_id' => $session->id]);
        }
    }
}