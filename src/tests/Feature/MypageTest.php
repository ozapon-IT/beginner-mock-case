<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Profile;

class MypageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_user_information_on_mypage()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::inRandomOrder()->first();
        $this->actingAs($user);

        $profile = Profile::create([
            'user_id' => $user->id,
            'image_path' => 'items/profile_image.png',
            'postal_code' => '111-1111',
            'address' => 'テストアドレス'
        ]);

        $sellItem = Item::inRandomOrder()->first();
        $sellItem->update([
            'user_id' => $user->id,
        ]);

        $buyItem = Item::where('user_id', '!=', $user->id)->inRandomOrder()->first();
        $buyItem->update([
            'status' => 'sold',
        ]);
        Order::create([
            'user_id' => $user->id,
            'item_id' => $buyItem->id,
            'payment_method' => 'テスト払い',
            'shipping_address' => 'テストアドレス',
            'status' => 'paid',
        ]);

        $response = $this->get(route('mypage', ['tab' => 'sell']));

        $response->assertStatus(200);

        $response->assertSee(asset('storage/' . $profile->image_path));
        $response->assertSee($user->name);

        $response->assertSee($sellItem->name);

        $response = $this->get(route('mypage', ['tab' => 'buy']));

        $response->assertStatus(200);

        $response->assertSee($buyItem->name);
    }
}
