<?php

namespace App\Http\Controllers\Member;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController
{
    public function __invoke(Request $request): View
    {
        $member = $request->user('member')->load([
            'classShirtOrders' => fn ($query) => $query->latest('submitted_at')->latest(),
        ]);

        return view('member.dashboard', [
            'member' => $member,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'birthday' => ['nullable', 'date'],
            'mom_name' => ['nullable', 'string', 'max:120'],
            'mom_phone' => ['nullable', 'string', 'max:40'],
            'dad_name' => ['nullable', 'string', 'max:120'],
            'dad_phone' => ['nullable', 'string', 'max:40'],
        ]);

        $request->user('member')->update($validated);

        return redirect()
            ->route('member.dashboard')
            ->with('status', 'Your family profile has been updated.');
    }
}
