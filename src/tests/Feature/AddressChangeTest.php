<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;
use App\Models\Condition;

class AddressChangeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_updates_address_and_reflects_in_purchase_page()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

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
}
