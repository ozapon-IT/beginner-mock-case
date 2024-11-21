<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function logged_in_user_can_submit_a_comment()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::inRandomOrder()->first();
        $this->actingAs($user);

        $item = Item::inRandomOrder()->first();

        $initialCommentCount = $item->comments()->count();

        $commentContent = 'テストコメント';

        $response = $this->post(route('comment', ['item' => $item->id]), [
            'content' => $commentContent,
        ]);

        $response->assertRedirect(route('item', ['item' => $item->id]));

        $this->assertDatabaseHas('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'content' => $commentContent,
        ]);

        $updatedCommentCount = $item->comments()->count();
        $this->assertEquals($initialCommentCount + 1, $updatedCommentCount);
    }

    /** @test */
    public function guest_cannot_submit_a_comment()
    {
        $this->seed(DatabaseSeeder::class);

        $item = Item::inRandomOrder()->first();

        $commentContent = 'テストコメント';

        $response = $this->post(route('comment', ['item' => $item->id]), [
            'content' => $commentContent,
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'content' => $commentContent,
        ]);
    }

    /** @test */
    public function it_shows_validation_error_when_comment_is_empty()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::inRandomOrder()->first();
        $this->actingAs($user);

        $item = Item::inRandomOrder()->first();

        $response = $this->post(route('comment', ['item' => $item->id]), [
            'content' => '',
        ]);

        $response->assertRedirect();

        $response->assertSessionHasErrors([
            'content' => 'コメントを入力してください。',
        ]);

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_shows_validation_error_when_comment_exceeds_255_characters()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::inRandomOrder()->first();
        $this->actingAs($user);

        $item = Item::inRandomOrder()->first();

        $longComment = str_repeat('あ', 256);

        $response = $this->post(route('comment', ['item' => $item->id]), [
            'content' => $longComment,
        ]);

        $response->assertRedirect();

        $response->assertSessionHasErrors([
            'content' => 'コメントは255文字以内で入力してください。',
        ]);

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);
    }
}
