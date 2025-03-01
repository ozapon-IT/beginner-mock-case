<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class LikeFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_allows_user_to_like_an_item_and_increments_like_count()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::inRandomOrder()->first();
        $this->actingAs($user);

        $item = Item::inRandomOrder()->first();

        $initialLikeCount = $item->likes()->count();

        $response = $this->post(route('like', ['item' => $item->id]));

        $response->assertRedirect();

        $this->assertDatabaseHas('likes', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $updatedLikeCount = $item->likes()->count();
        $this->assertEquals($initialLikeCount + 1, $updatedLikeCount);
    }

    /** @test */
    public function it_changes_like_icon_color_when_item_is_liked()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::inRandomOrder()->first();
        $this->actingAs($user);

        $item = Item::inRandomOrder()->first();

        $response = $this->get(route('item', ['item' => $item->id]));
        $response->assertStatus(200);
        $response->assertSee('bi bi-star');

        $this->post(route('like', ['item' => $item->id]));

        $response = $this->get(route('item', ['item' => $item->id]));
        $response->assertStatus(200);
        $response->assertSee('bi bi-star-fill');
    }

    /** @test */
    public function it_allows_user_to_unlike_an_item_and_decrements_like_count()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::inRandomOrder()->first();
        $this->actingAs($user);

        $item = Item::inRandomOrder()->first();

        $item->likes()->create(['user_id' => $user->id]);

        $initialLikeCount = $item->likes()->count();
        $this->assertEquals(1, $initialLikeCount);

        $response = $this->delete(route('unlike', ['item' => $item->id]));

        $response->assertRedirect();

        $this->assertDatabaseMissing('likes', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $updatedLikeCount = $item->likes()->count();
        $this->assertEquals(0, $updatedLikeCount);
    }
}
