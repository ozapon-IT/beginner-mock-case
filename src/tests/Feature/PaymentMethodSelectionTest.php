<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Condition;
use App\Models\Item;
use App\Models\Profile;

class PaymentMethodSelectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_reflects_selected_payment_method_immediately()
    {
        $user = User::factory()->create()->first();
        $otherUser = User::factory()->create()->first();

        $condition = Condition::factory()->create();

        $item = Item::create([
            'user_id' => $otherUser->id,
            'condition_id' => $condition->id,
            'image_path' => 'items/test_image.png',
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト商品の説明',
            'price' => 1000.00,
            'status' => '在庫あり',
        ]);

        $profile = Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト町1-2-3',
            'building' => 'テストビル301',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('purchase', ['item' => $item->id]));

        $response->assertStatus(200);

        $response->assertSee('支払い方法');
        $response->assertSee('<option value="コンビニ払い"', false);
        $response->assertSee('<option value="カード払い"', false);

        session()->put('payment_method', 'カード払い');

        $response = $this->get(route('purchase', ['item' => $item->id]));

        $response->assertStatus(200);
        $response->assertSee('カード払い');
    }
}
