<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

class ProfileSettingsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_correct_initial_values_on_profile_settings_page()
    {
        User::factory(10)->create();

        $user = User::inRandomOrder()->first();
        $this->actingAs($user);

        $profile = Profile::create([
            'user_id' => $user->id,
            'image_path' => 'profiles/test_image.png',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト町1-2-3',
            'building' => 'テストビル301'
        ]);

        $response = $this->get(route('profile'));

        $response->assertStatus(200);

        $response->assertSee(Storage::url($profile->image_path));
        $response->assertSee($user->name);
        $response->assertSee($profile->postal_code);
        $response->assertSee($profile->address);
        $response->assertSee($profile->building);
    }
}
