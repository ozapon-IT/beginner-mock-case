<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SellItemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_allows_logged_in_user_to_save_item_with_valid_data()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $categories = Category::factory(3)->create();
        $condition = Condition::factory()->create();

        $this->actingAs($user);

        $dummyFile = UploadedFile::fake()->create('test_image.jpg', 100, 'image/jpeg');

        $data = [
            'name' => 'テスト商品',
            'description' => 'テスト商品の説明',
            'price' => 1000,
            'condition_id' => $condition->id,
            'image_path' => $dummyFile,
            'category_id' => $categories->pluck('id')->toArray(),
            'brand' => 'テストブランド',
        ];

        $response = $this->post(route('sell.item'), $data);

        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
            'description' => 'テスト商品の説明',
            'price' => 1000,
            'condition_id' => $condition->id,
            'brand' => 'テストブランド',
            'user_id' => $user->id,
            'image_path' => 'items/' . $dummyFile->hashName(),
        ]);

        $item = Item::first();
        Storage::disk('public')->assertExists($item->image_path);

        foreach ($categories as $category) {
            $this->assertDatabaseHas('category_items', [
                'item_id' => $item->id,
                'category_id' => $category->id,
            ]);
        }
    }
}
