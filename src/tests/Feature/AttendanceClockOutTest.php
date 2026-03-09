<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceClockOutTest extends TestCase
{
    use RefreshDatabase;

    public function test_退勤ボタンが正しく機能()
    {
        $user = $this->createRoleUser();

        $this->createAttendanceFor($user, [
            'in_at' => now()->subHour(),
        ]);

        $attendancePage = $this->actingAs($user)->get('/attendance');

        $attendancePage->assertStatus(200);
        $attendancePage->assertSee('退勤');

        $this->post('/attendance/clock-out')
            ->assertRedirect('/attendance');

            $this->get('/attendance')
            ->assertStatus(200)
            ->assertSee('退勤済');
    }

    public function test_出勤と退勤時刻を一覧で確認()
    {
        $user = $this->createRoleUser();

        $clockInAt = Carbon::create(2026, 3, 8, 9, 0, 0, 'Asia/Tokyo');
        $clockOutAt = Carbon::create(2026, 3, 8, 18, 0, 0, 'Asia/Tokyo');

        $this->createAttendanceFor($user, [
            'date' => $clockInAt->toDateString(),
            'in_at' => $clockInAt,
            'out_at' => $clockOutAt,
        ]);

        Carbon::setTestNow($clockInAt);
        $this->actingAs($user)
            ->post('/attendance/clock-in')
            ->assertRedirect('/attendance');

        Carbon::setTestNow($clockOutAt);
        $this->post('/attendance/clock-out')
            ->assertRedirect('/attendance');

        $response = $this->get('/attendance/list');

        $response->assertStatus(200);
        $response->assertSee($clockInAt->format('m/d') . '(' . $this->jpWeekday($clockInAt->dayOfWeek) . ')');
        $response->assertSee($clockInAt->format('H:i'));
        $response->assertSee($clockOutAt->format('H:i'));

        Carbon::setTestNow();
    }
}
