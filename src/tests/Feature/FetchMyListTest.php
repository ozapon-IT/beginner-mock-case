<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use Database\Seeders\DatabaseSeeder;

class FetchMyListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_only_liked_items_in_mylist()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::inRandomOrder()->first();

        $likedItems = Item::inRandomOrder()->take(3)->get();
        foreach ($likedItems as $item) {
            Like::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
        }

        $this->actingAs($user);

        $response = $this->get(route('top', ['tab' => 'mylist']));

        $response->assertStatus(200);

        $response->assertViewHas('items', function ($items) use ($likedItems) {
            return $items->count() === $likedItems->count();
        });
    }

    /** @test */
    public function it_displays_sold_label_for_purchased_items_in_mylist()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::inRandomOrder()->first();

        $likedItem = Item::inRandomOrder()->first();
        Like::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $likedItem->update(['status' => 'sold']);

        $this->actingAs($user);

        $response = $this->get(route('top', ['tab' => 'mylist']));

        $response->assertStatus(200);

        $response->assertSee('SOLD');

        $response->assertSee($likedItem->name);
    }

    /** @test */
    public function it_does_not_display_items_listed_by_the_authenticated_user_in_mylist()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::inRandomOrder()->first();

        $itemByUser = Item::inRandomOrder()->first();
        $itemByUser->update(['user_id' => $user->id]);

        $itemByOtherUser = Item::where('user_id', '!=', $user->id)->inRandomOrder()->first();

        Like::create([
            'user_id' => $user->id,
            'item_id' => $itemByOtherUser->id,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('top', ['tab' => 'mylist']));

        $response->assertStatus(200);

        $response->assertDontSee($itemByUser->name);

        $response->assertSee($itemByOtherUser->name);
    }

    /** @test */
    public function it_displays_nothing_in_mylist_for_unauthenticated_users()
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->get(route('top', ['tab' => 'mylist']));

        $response->assertStatus(200);

        $response->assertDontSee('<div class="product-grid__item">', false);
    }
}
