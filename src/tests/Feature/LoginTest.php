<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_validation_message_when_email_is_missing()
    {
        $data = [
            'email' => '',
            'password' => 'password123',
        ];

        $response = $this->post(route('login'), $data);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください。']);
    }

    /** @test */
    public function it_shows_validation_message_when_password_is_missing()
    {
        $data = [
            'email' => 'test@example.com',
            'password' => '',
        ];

        $response = $this->post(route('login'), $data);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください。']);
    }

    /** @test */
    public function it_shows_error_message_when_credentials_are_incorrect()
    {
        $data = [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword123',
        ];

        $response = $this->post(route('login'), $data);

        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません。']);
    }

    /** @test */
    public function it_logs_in_a_user_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        $data = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $response = $this->post(route('login'), $data);

        $this->assertAuthenticatedAs($user);

        $response->assertRedirect(route('profile'));
    }
}
