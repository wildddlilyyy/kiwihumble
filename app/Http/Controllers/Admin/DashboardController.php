<?php

namespace App\Http\Controllers\Admin;

use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'settings' => SiteSetting::query()->orderBy('key')->get(),
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
            ->route('admin.dashboard')
            ->with('status', 'Site settings updated.');
    }
}
