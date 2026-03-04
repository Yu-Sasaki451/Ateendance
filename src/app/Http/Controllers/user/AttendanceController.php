<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\BreakTime;

class AttendanceController extends Controller
{
    public function index()
    {
        $now = now();

        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('date', today())
            ->first();

        $status = '勤務外';
        $buttons = [];
        $message = null;

        $currentBreak = null;

        if ($attendance) {
            $currentBreak = BreakTime::where('attendance_id', $attendance->id)
                ->whereNotNull('in_at')
                ->whereNull('out_at')
                ->latest()
                ->first();
}


        if ($attendance && $attendance->out_at) {
            $status = '退勤済';
            $message = 'お疲れ様でした。';
        } elseif ($attendance && $currentBreak) {
            $status = '休憩中';
            $buttons = [
                ['label' => '休憩戻', 'route' => route('attendance.break-end'),'type' => 'light'],
            ];
        } elseif ($attendance && $attendance->in_at) {
            $status = '出勤中';
            $buttons = [
                ['label' => '退勤', 'route' => route('attendance.clock-out'),'type' => 'dark'],
                ['label' => '休憩入', 'route' => route('attendance.break-start'),'type' => 'light'],
                ];
        } else{
            $buttons = [
                ['label' => '出勤','route' => route('attendance.clock-in'),'type' => 'dark'],
            ];
        }

        return view('user.attendance', [
            'today' => $now->format('Y年n月j日'),
            'weekday' => ['日', '月', '火', '水', '木', '金', '土'][$now->dayOfWeek],
            'currentTime' => $now->format('H:i'),
            'status' => $status,
            'attendance' => $attendance,
            'buttons' => $buttons,
            'message' => $message,
        ]);
    }

    private function getTodayAttendance(){
    return Attendance::where('user_id', auth()->id())
        ->whereDate('date', today())
        ->first();
    }

    private function getOrCreateTodayAttendance(){
    return Attendance::firstOrCreate(
        [
            'user_id' => auth()->id(),
            'date' => today(),
        ]
    );
    }

    private function getCurrentBreak($attendanceId){
    return BreakTime::where('attendance_id', $attendanceId)
        ->whereNotNull('in_at')
        ->whereNull('out_at')
        ->latest()
        ->first();
    }

    public function clockIn(){
    $attendance = $this->getOrCreateTodayAttendance();

    $attendance->update([
        'in_at' => now(),
    ]);

    return redirect()->route('user.attendance');
    }

    public function clockOut(){
    $attendance = $this->getTodayAttendance();

    if ($attendance) {
        $attendance->update([
            'out_at' => now(),
        ]);
    }

    return redirect()->route('user.attendance');
    }

    public function breakStart(){
    $attendance = $this->getTodayAttendance();

    if ($attendance) {
        BreakTime::create([
            'attendance_id' => $attendance->id,
            'in_at' => now(),
        ]);
    }

    return redirect()->route('user.attendance');
    }

    public function breakEnd(){
    $attendance = $this->getTodayAttendance();

    if ($attendance) {
        $breakTime = $this->getCurrentBreak($attendance->id);

        if ($breakTime) {
            $breakTime->update([
                'out_at' => now(),
            ]);
        }
    }

    return redirect()->route('user.attendance');
    }



}
