<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use App\Models\Item;

class FetchItemsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_all_items_for_guest_user()
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->get(route('top'));

        $response->assertStatus(200);

        $response->assertViewHas('items', function ($items) {
            return $items->count() === 10;
        });
    }

    /** @test */
    public function it_displays_sold_label_for_sold_items()
    {
        $this->seed(DatabaseSeeder::class);

        $soldItem = Item::inRandomOrder()->first();
        $soldItem->update(['status' => 'sold']);

        $response = $this->get(route('top'));

        $response->assertStatus(200);

        $response->assertSee('SOLD');
    }

    /** @test */
    public function it_does_not_display_items_listed_by_the_authenticated_user()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::inRandomOrder()->first();

        $itemByUser = Item::inRandomOrder()->first();

        $itemByUser->update(['user_id' => $user->id]);

        $itemsByOtherUsers = Item::where('user_id', '!=', $user->id)->get();

        $this->actingAs($user);

        $response = $this->get(route('top'));

        $response->assertStatus(200);

        $response->assertDontSee($itemByUser->name);

        foreach ($itemsByOtherUsers as $item) {
            $response->assertSee($item->name);
        }
    }
}
