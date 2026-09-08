<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\SiteSetting;
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
        return view('backend.trip', [
            'members' => $this->members(),
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
