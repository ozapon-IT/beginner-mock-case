<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\Item;

class SearchItemsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_search_items_by_partial_name()
    {
        $this->seed(DatabaseSeeder::class);

        $searchItem = Item::inRandomOrder()->first();
        $searchItem->update(['name' => 'テスト商品']);

        $nonMatchingItems = Item::where('id', '!=', $searchItem->id)->take(3)->get();

        $searchKeyword = '商品';

        $response = $this->get(route('top', ['search' => $searchKeyword]));

        $response->assertStatus(200);

        $response->assertSee($searchItem->name);

        foreach ($nonMatchingItems as $item) {
            $response->assertDontSee($item->name);
        }
    }

    /** @test */
    public function it_keeps_search_keyword_on_mylist_page()
    {
        $this->seed(DatabaseSeeder::class);

        $searchItem = Item::inRandomOrder()->first();
        $searchItem->update(['name' => 'テスト商品']);

        $searchKeyword = '商品';

        $response = $this->get(route('top', ['search' => $searchKeyword]));
        $response->assertStatus(200);
        $response->assertSee($searchItem->name);
        $response->assertSee($searchKeyword);

        $mylistResponse = $this->get(route('top', ['search' => $searchKeyword, 'tab' => 'mylist']));
        $mylistResponse->assertStatus(200);

        $mylistResponse->assertSee($searchKeyword);
    }
}
