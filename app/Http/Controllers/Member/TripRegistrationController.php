<?php

namespace App\Http\Controllers\Member;

use App\Models\TripRegistration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TripRegistrationController
{
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'adults_count' => ['required', 'integer', 'min:0', 'max:20'],
            'children_count' => ['required', 'integer', 'min:0', 'max:20'],
            'child_ages' => ['nullable', 'array', 'max:20'],
            'child_ages.*' => ['required', 'integer', 'min:0', 'max:18'],
            'room_count' => ['required', 'integer', 'min:0', 'max:10'],
            'room_types' => ['nullable', 'array', 'max:10'],
            'room_types.*' => ['required', Rule::in(array_keys(TripRegistration::ROOM_TYPES))],
        ], [
            'child_ages.*.required' => '請填寫每位小孩的年齡。',
            'child_ages.*.integer' => '小孩年齡請填寫整數。',
            'child_ages.*.between' => '小孩年齡需介於 0 到 18 歲。',
        ]);

        $validator->after(function ($validator) use ($request): void {
            $childrenCount = (int) $request->input('children_count', 0);
            $childAges = array_values($request->input('child_ages', []));
            $roomCount = (int) $request->input('room_count', 0);
            $roomTypes = array_values($request->input('room_types', []));

            if (count($childAges) !== $childrenCount) {
                $validator->errors()->add('child_ages', '小孩人數與填寫的年齡數量不一致。');
            }

            if (count($roomTypes) !== $roomCount) {
                $validator->errors()->add('room_types', '房間數量與選擇的房型數量不一致。');
            }
        });

        $validated = $validator->validate();
        $member = $request->user('member');

        $registration = $member->tripRegistration()->updateOrCreate([], [
            'adults_count' => (int) $validated['adults_count'],
            'children_count' => (int) $validated['children_count'],
            'child_ages' => array_map('intval', array_values($validated['child_ages'] ?? [])),
            'room_count' => (int) $validated['room_count'],
            'room_types' => array_values($validated['room_types'] ?? []),
            'submitted_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => '畢旅人數及房間登記已送出。',
                'submitted_at' => $registration->submitted_at?->timezone(config('app.timezone'))->format('Y-m-d H:i'),
            ]);
        }

        return redirect()
            ->route('member.dashboard', ['tab' => 'trip'])
            ->with('status', '畢旅人數及房間登記已送出。');
    }
}
