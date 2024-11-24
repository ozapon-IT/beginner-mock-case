<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Category;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_all_required_information_on_item_detail_page()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::inRandomOrder()->first();

        $item = Item::inRandomOrder()->first();
        $item->update([
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => 1000,
            'description' => 'テスト商品の説明文',
        ]);

        $categories = $item->categories;

        Like::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        Comment::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'content' => 'テストコメント'
        ]);

        $response = $this->get(route('item', ['item' => $item->id]));

        $response->assertStatus(200);

        $response->assertSee(asset('storage/' . $item->image_path));
        $response->assertSee($item->name);
        $response->assertSee($item->brand);
        $response->assertSee('¥1,000');
        $response->assertSee($item->description);
        $response->assertSee($item->condition->name);

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }

        $response->assertSee((string) $item->likes->count());
        $response->assertSee((string) $item->comments->count());

        foreach ($item->comments as $comment) {
            $response->assertSee($comment->content);
            $response->assertSee($comment->user->name);
        }
    }

    /** @test */
    public function it_displays_all_selected_categories_on_item_detail_page()
    {
        $this->seed(DatabaseSeeder::class);

        $item = Item::inRandomOrder()->first();

        $categories = Category::inRandomOrder()->take(3)->get();
        $item->categories()->sync($categories->pluck('id'));

        $response = $this->get(route('item', ['item' => $item->id]));

        $response->assertStatus(200);

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }
}
