<?php

namespace Tests\Feature;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_会員登録時に認証メールが送信される()
    {
        Notification::fake();

        $email = 'verify_test@example.com';

        $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => $email,
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $user = \App\Models\User::where('email', $email)->first();

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_未認証ユーザーは勤怠画面にアクセスできない()
    {
        $user = $this->createRoleUser([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user)
            ->get('/attendance')
            ->assertRedirect('/email/verify');
    }
}
