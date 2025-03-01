<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Order;
use Mockery;
use Stripe\Checkout\Session as StripeSession;


class PurchaseItemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_completes_purchase_when_buy_button_is_pressed()
    {
        // Stripeのモック
        $mockedSession = Mockery::mock('overload:Stripe\Checkout\Session');
        $mockedSession->shouldReceive('create')
            ->once()
            ->andReturn((object)[
                'id' => 'test_session_id',
                'url' => 'https://checkout.stripe.com/test-session',
            ]);

        $user = User::factory()->create();
        $seller = User::factory()->create();
        $condition = Condition::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'image_path' => 'items/test_image.png',
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト商品の説明',
            'price' => 1000.00,
            'status' => '在庫あり',
        ]);

        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト町1-2-3',
            'building' => 'テストビル301',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('purchase', ['item' => $item->id]));
        $response->assertStatus(200);

        $purchaseData = [
            'payment_method' => 'カード払い',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト町1-2-3',
            'building' => 'テストビル301',
        ];

        $response = $this->post(route('purchase.item', ['item' => $item->id]), $purchaseData);

        $response->assertRedirect('https://checkout.stripe.com/test-session');

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'status' => 'pending',
            'payment_method' => 'カード払い',
            'shipping_address' => '〒123-4567 東京都渋谷区テスト町1-2-3 テストビル301',
            'stripe_session_id' => 'test_session_id',
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'transaction',
        ]);

        $order = Order::where('user_id', $user->id)->first();

        // Stripe Webhook用のモックペイロード
        $webhookPayload = [
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'test_payment_intent_id',
                    'metadata' => [
                        'order_id' => $order->id,
                    ],
                ],
            ],
        ];

        // Stripe\Webhook::constructEvent をモック
        $mockedWebhook = Mockery::mock('alias:Stripe\Webhook');
        $mockedWebhook->shouldReceive('constructEvent')
            ->andReturn(json_decode(json_encode($webhookPayload)));

        $response = $this->postJson('/stripe/webhook', $webhookPayload);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid',
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'sold',
        ]);
    }

    /** @test */
    public function purchased_item_is_displayed_as_sold_on_item_list()
    {
        // Stripeのモック
        $mockedSession = Mockery::mock('overload:Stripe\Checkout\Session');
        $mockedSession->shouldReceive('create')
            ->once()
            ->andReturn((object)[
                'id' => 'test_session_id',
                'url' => 'https://checkout.stripe.com/test-session',
            ]);

        $user = User::factory()->create();
        $seller = User::factory()->create();
        $condition = Condition::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'image_path' => 'items/test_image.png',
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト商品の説明',
            'price' => 1000.00,
            'status' => '在庫あり',
        ]);

        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト町1-2-3',
            'building' => 'テストビル301',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('purchase', ['item' => $item->id]));
        $response->assertStatus(200);

        $purchaseData = [
            'payment_method' => 'カード払い',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト町1-2-3',
            'building' => 'テストビル301',
        ];

        $response = $this->post(route('purchase.item', ['item' => $item->id]), $purchaseData);

        $response->assertRedirect('https://checkout.stripe.com/test-session');

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'status' => 'pending',
            'payment_method' => 'カード払い',
            'shipping_address' => '〒123-4567 東京都渋谷区テスト町1-2-3 テストビル301',
            'stripe_session_id' => 'test_session_id',
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'transaction',
        ]);

        $order = Order::where('user_id', $user->id)->first();

        // Stripe Webhook用のモックペイロード
        $webhookPayload = [
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'test_payment_intent_id',
                    'metadata' => [
                        'order_id' => $order->id,
                    ],
                ],
            ],
        ];

        // Stripe\Webhook::constructEvent をモック
        $mockedWebhook = Mockery::mock('alias:Stripe\Webhook');
        $mockedWebhook->shouldReceive('constructEvent')
            ->andReturn(json_decode(json_encode($webhookPayload)));

        $response = $this->postJson('/stripe/webhook', $webhookPayload);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid',
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'sold',
        ]);

        $response = $this->get(route('top'));

        $response->assertStatus(200);
        $response->assertSee('SOLD');
        $response->assertSee($item->name);
    }

    /** @test */
    public function purchased_item_is_added_to_profile_purchase_list()
    {
        // Stripeのモック
        $mockedSession = Mockery::mock('overload:Stripe\Checkout\Session');
        $mockedSession->shouldReceive('create')
            ->once()
            ->andReturn((object)[
                'id' => 'test_session_id',
                'url' => 'https://checkout.stripe.com/test-session',
            ]);

        $user = User::factory()->create();
        $seller = User::factory()->create();
        $condition = Condition::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'image_path' => 'items/test_image.png',
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト商品の説明',
            'price' => 1000.00,
            'status' => '在庫あり',
        ]);

        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト町1-2-3',
            'building' => 'テストビル301',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('purchase', ['item' => $item->id]));
        $response->assertStatus(200);

        $purchaseData = [
            'payment_method' => 'カード払い',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト町1-2-3',
            'building' => 'テストビル301',
        ];

        $response = $this->post(route('purchase.item', ['item' => $item->id]), $purchaseData);

        $response->assertRedirect('https://checkout.stripe.com/test-session');

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'status' => 'pending',
            'payment_method' => 'カード払い',
            'shipping_address' => '〒123-4567 東京都渋谷区テスト町1-2-3 テストビル301',
            'stripe_session_id' => 'test_session_id',
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'transaction',
        ]);

        $order = Order::where('user_id', $user->id)->first();

        // Stripe Webhook用のモックペイロード
        $webhookPayload = [
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'test_payment_intent_id',
                    'metadata' => [
                        'order_id' => $order->id,
                    ],
                ],
            ],
        ];

        // Stripe\Webhook::constructEvent をモック
        $mockedWebhook = Mockery::mock('alias:Stripe\Webhook');
        $mockedWebhook->shouldReceive('constructEvent')
            ->andReturn(json_decode(json_encode($webhookPayload)));

        $response = $this->postJson('/stripe/webhook', $webhookPayload);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid',
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'sold',
        ]);

        $response = $this->get(route('mypage', ['tab' => 'buy']));

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
