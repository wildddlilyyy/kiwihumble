<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\SiteSetting;
use App\Models\TripRegistration;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController
{
    public function __invoke(): View
    {
        return view('backend.dashboard');
    }

    public function trip(): View
    {
        $members = $this->members();
        $registrations = $members->pluck('tripRegistration')->filter();
        $childAges = $registrations->flatMap(fn (TripRegistration $registration) => $registration->child_ages ?? []);
        $roomTypes = $registrations->flatMap(fn (TripRegistration $registration) => $registration->room_types ?? []);

        return view('backend.trip', [
            'members' => $members,
            'stats' => [
                'groups' => $registrations->count(),
                'adults' => $registrations->sum('adults_count'),
                'children' => $registrations->sum('children_count'),
                'children_0_6' => $childAges->filter(fn ($age) => (int) $age <= 6)->count(),
                'children_7_12' => $childAges->filter(fn ($age) => (int) $age >= 7 && (int) $age <= 12)->count(),
                'rooms_double' => $roomTypes->filter(fn ($type) => $type === TripRegistration::ROOM_DOUBLE)->count(),
                'rooms_quad' => $roomTypes->filter(fn ($type) => $type === TripRegistration::ROOM_QUAD)->count(),
                'rooms_six' => $roomTypes->filter(fn ($type) => $type === TripRegistration::ROOM_SIX)->count(),
            ],
        ]);
    }

    public function shirts(): View
    {
        return view('backend.shirts', [
            'members' => $this->members(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'trip_title' => ['required', 'string', 'max:120'],
            'trip_date' => ['required', 'date'],
            'timezone' => ['required', 'timezone'],
        ]);

        $types = [
            'trip_title' => 'string',
            'trip_date' => 'date',
            'timezone' => 'timezone',
        ];

        foreach ($validated as $key => $value) {
            SiteSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => $types[$key]],
            );
        }

        return redirect()
            ->route('backend.members.index')
            ->with('status', 'Site settings updated.');
    }

    private function members()
    {
        return User::query()
            ->where('is_admin', false)
            ->with(['classShirtOrder', 'tripRegistration'])
            ->orderBy('name')
            ->get();
    }
}
