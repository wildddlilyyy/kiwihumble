<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MemberController
{
    public function index(): View
    {
        return view('backend.members.index', [
            'members' => User::query()
                ->where('is_admin', false)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('backend.members.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'phone' => ['nullable', 'string', 'max:40'],
            'mom_name' => ['nullable', 'string', 'max:120'],
            'dad_name' => ['nullable', 'string', 'max:120'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::query()->create([
            'name' => $validated['name'],
            'email' => null,
            'phone' => $validated['phone'] ?? null,
            'mom_name' => $validated['mom_name'] ?? null,
            'dad_name' => $validated['dad_name'] ?? null,
            'password' => Hash::make($validated['password']),
            'is_admin' => false,
        ]);

        return redirect()
            ->route('backend.members.index')
            ->with('status', 'Member created.');
    }

    public function edit(User $member): View
    {
        $this->ensureMember($member);

        return view('backend.members.edit', [
            'member' => $member,
        ]);
    }

    public function update(Request $request, User $member): RedirectResponse
    {
        $this->ensureMember($member);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('users', 'name')->ignore($member)],
            'phone' => ['nullable', 'string', 'max:40'],
            'mom_name' => ['nullable', 'string', 'max:120'],
            'dad_name' => ['nullable', 'string', 'max:120'],
        ]);

        $member->update($validated);

        return redirect()
            ->route('backend.members.index')
            ->with('status', 'Member updated.');
    }

    public function updatePassword(Request $request, User $member): RedirectResponse
    {
        $this->ensureMember($member);

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $member->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('backend.members.edit', $member)
            ->with('status', 'Member password updated.');
    }

    private function ensureMember(User $user): void
    {
        abort_if($user->is_admin, 404);
    }
}
