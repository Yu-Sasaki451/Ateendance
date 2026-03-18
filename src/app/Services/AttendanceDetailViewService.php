<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\CorrectionRequest;
use Carbon\Carbon;

class AttendanceDetailViewService
{
    public function build(
        Attendance $attendance,
        ?CorrectionRequest $correctionRequest,
        bool $isAdmin,
        bool $isApproved,
        string $breakLabel
    ): array {
        $isPending = $correctionRequest?->status === 'pending';
        $breakTimes = $correctionRequest
            ? $correctionRequest->breakTimes
            : $attendance->breakTimes;

        $breakRows = $breakTimes
            ->values()
            ->map(function ($breakTime) use ($correctionRequest) {
                return [
                    'in_at' => $correctionRequest
                        ? $this->formatTime($breakTime->requested_in_at)
                        : $this->formatTime($breakTime->in_at),
                    'out_at' => $correctionRequest
                        ? $this->formatTime($breakTime->requested_out_at)
                        : $this->formatTime($breakTime->out_at),
                ];
            })
            ->filter(function ($breakRow) {
                return $breakRow['in_at'] !== '' || $breakRow['out_at'] !== '';
            })
            ->values()
            ->map(function ($breakRow, $index) use ($breakLabel) {
                $breakRow['label'] = $index === 0 ? $breakLabel : $breakLabel . ($index + 1);

                return $breakRow;
            })
            ->all();

        if (!$isPending && !$isApproved) {
            $nextIndex = count($breakRows);
            $breakRows[] = [
                'label' => $nextIndex === 0 ? $breakLabel : $breakLabel . ($nextIndex + 1),
                'in_at' => '',
                'out_at' => '',
            ];
        }

        return [
            'attendance' => $attendance,
            'dateYearLabel' => $this->formatDate($attendance->date, 'Y年'),
            'dateMonthDayLabel' => $this->formatDate($attendance->date, 'n月j日'),
            'inAtLabel' => $this->resolveTimeLabel(
                $correctionRequest?->requested_in_at,
                $attendance->in_at
            ),
            'outAtLabel' => $this->resolveTimeLabel(
                $correctionRequest?->requested_out_at,
                $attendance->out_at
            ),
            'noteLabel' => $correctionRequest ? $correctionRequest->note : $attendance->note,
            'breakRows' => $breakRows,
            'userName' => $attendance->user->name ?? '',
            'isPending' => $isPending,
            'isApproved' => $isApproved,
            'isAdmin' => $isAdmin,
        ];
    }

    private function formatDate($value, string $format): string
    {
        return $value ? Carbon::parse($value)->format($format) : '';
    }

    private function formatTime($value): string
    {
        return $value ? Carbon::parse($value)->format('H:i') : '';
    }

    private function resolveTimeLabel($requestedValue, $originalValue): string
    {
        if ($requestedValue) {
            return $this->formatTime($requestedValue);
        }

        return $this->formatTime($originalValue);
    }
}
