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

class AddressChangeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_updates_address_and_reflects_in_purchase_page()
    {
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
        $response->assertSee('〒 123-4567');
        $response->assertSee('東京都渋谷区テスト町1-2-3');
        $response->assertSee('テストビル301');

        $newAddress = [
            'postal_code' => '987-6543',
            'address' => '東京都新宿区変更町4-5-6',
            'building' => '変更ビル401',
        ];

        $response = $this->post(route('address.change', ['item' => $item->id]), $newAddress);
        $response->assertRedirect(route('purchase', ['item' => $item->id]));

        $response = $this->get(route('purchase', ['item' => $item->id]));
        $response->assertStatus(200);
        $response->assertSee('〒 987-6543');
        $response->assertSee('東京都新宿区変更町4-5-6');
        $response->assertSee('変更ビル401');
    }

    /** @test */
    public function it_registers_correct_shipping_address_with_purchased_item()
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

        $newAddress = [
            'postal_code' => '987-6543',
            'address' => '東京都新宿区変更町4-5-6',
            'building' => '変更ビル401',
        ];
        $response = $this->post(route('address.change', ['item' => $item->id]), $newAddress);
        $response->assertRedirect(route('purchase', ['item' => $item->id]));

        $response = $this->get(route('purchase', ['item' => $item->id]));
        $response->assertStatus(200);

        $purchaseData = [
            'payment_method' => 'カード払い',
            'postal_code' => $newAddress['postal_code'],
            'address' => $newAddress['address'],
            'building' => $newAddress['building'],
        ];
        $response = $this->post(route('purchase.item', ['item' => $item->id]), $purchaseData);

        $response->assertRedirect('https://checkout.stripe.com/test-session');

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

        // Stripe Webhookのモック
        $mockedWebhook = Mockery::mock('alias:Stripe\Webhook');
        $mockedWebhook->shouldReceive('constructEvent')
            ->andReturn(json_decode(json_encode($webhookPayload)));

        $response = $this->postJson('/stripe/webhook', $webhookPayload);

        $response->assertStatus(200);

        // データベースの確認
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'status' => 'paid',
            'shipping_address' => "〒{$newAddress['postal_code']} {$newAddress['address']} {$newAddress['building']}",
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'sold',
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
