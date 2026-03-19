<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CorrectionRequest;
use App\Services\AttendanceDetailViewService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class CorrectionRequestController extends Controller
{
    private AttendanceDetailViewService $attendanceDetailViewService;

    public function __construct(AttendanceDetailViewService $attendanceDetailViewService)
    {
        $this->attendanceDetailViewService = $attendanceDetailViewService;
    }

    public function correctionIndex(Request $request)
    {
        $formatRequest = function ($request) {
            return [
                'status_label' => $request->status === 'pending' ? '承認待ち' : '承認済み',
                'user_name' => $request->attendance->user->name,
                'target_date' => Carbon::parse($request->requested_in_at)->format('Y/m/d'),
                'reason' => $request->reason,
                'applied_date' => $request->created_at->format('Y/m/d'),
                'detail_url' => route('admin.correction.show', [
                    'id' => $request->id,
                ]),
            ];
        };

        $activeTab = $request->query('tab', 'pending');

        $pendingRequests = CorrectionRequest::with('attendance.user')
            ->where('status', 'pending')
            ->latest()
            ->get()
            ->map($formatRequest);

        $approvedRequests = CorrectionRequest::with('attendance.user')
            ->where('status', 'approved')
            ->latest()
            ->get()
            ->map($formatRequest);

        return view('admin.correction_request_index', compact('pendingRequests', 'approvedRequests', 'activeTab'));
    }

    public function show($id)
    {
        $correctionRequest = CorrectionRequest::with([
            'attendance.user',
            'attendance.breakTimes',
            'attendance.correctionRequests.breakTimes',
            'breakTimes',
        ])->findOrFail($id);

        $attendance = $correctionRequest->attendance;

        return view('admin.detail', array_merge(
            [
                'correctionRequest' => $correctionRequest,
                'fromCorrectionRequestList' => true,
                'showPendingMessage' => false,
            ],
            $this->attendanceDetailViewService->build(
                $attendance,
                $correctionRequest,
                true,
                $correctionRequest->status === 'approved',
                '休憩'
            )
        ));
    }

    public function approve($id){

        $correctionRequest = CorrectionRequest::with(['attendance', 'breakTimes'])
            ->findOrFail($id);

        $attendance = $correctionRequest->attendance;

        DB::transaction(function() use ($correctionRequest,$attendance){
        $attendance->update([
            'in_at' => $correctionRequest->requested_in_at,
            'out_at' => $correctionRequest->requested_out_at,
            'note' => $correctionRequest->note,
        ]);

        $attendance->breakTimes()->delete();

        foreach ($correctionRequest->breakTimes as $breakTime) {
            $attendance->breakTimes()->create([
                'in_at' => $breakTime->requested_in_at,
                'out_at' => $breakTime->requested_out_at,
            ]);
        }

        $correctionRequest->update([
            'status' => 'approved',
        ]);
        });

        return redirect()->route('admin.correction.show', ['id' => $correctionRequest->id]);

    }
}
