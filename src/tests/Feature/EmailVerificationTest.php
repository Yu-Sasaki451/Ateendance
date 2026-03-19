<?php

namespace Tests\Feature;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
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

    public function test_メール認証誘導画面の認証はこちらからボタンで認証サイトへ遷移できる()
    {
        $user = $this->createRoleUser([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user)
            ->get('/email/verify')
            ->assertOk()
            ->assertSee('認証はこちらから')
            ->assertSee('href="http://localhost:8025"', false);
    }

    public function test_メール認証完了後に勤怠登録画面へ遷移する()
    {
        $user = $this->createRoleUser([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $this->actingAs($user)
            ->get($verificationUrl)
            ->assertRedirect('/attendance?verified=1');

        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
