<?php

namespace App\Http\Controllers\Backend;

use App\Models\ClassShirtOrder;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MemberController
{
    public function index(): View
    {
        return view('backend.members.index', [
            'members' => User::query()
                ->where('is_admin', false)
                ->with('classShirtOrder')
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
            'birthday' => ['nullable', 'date'],
            'mom_name' => ['nullable', 'string', 'max:120'],
            'mom_phone' => ['nullable', 'string', 'max:40'],
            'dad_name' => ['nullable', 'string', 'max:120'],
            'dad_phone' => ['nullable', 'string', 'max:40'],
            'password' => ['required', 'string', 'confirmed'],
        ]);

        User::query()->create([
            'name' => $validated['name'],
            'email' => null,
            'birthday' => $validated['birthday'] ?? null,
            'mom_name' => $validated['mom_name'] ?? null,
            'mom_phone' => $validated['mom_phone'] ?? null,
            'dad_name' => $validated['dad_name'] ?? null,
            'dad_phone' => $validated['dad_phone'] ?? null,
            'login_password' => $validated['password'],
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

    public function showClassShirtOrder(User $member): View
    {
        $this->ensureMember($member);

        return view('backend.members.class-shirt-order', [
            'member' => $member->load('classShirtOrder'),
        ]);
    }

    public function updateClassShirtOrder(Request $request, User $member): RedirectResponse
    {
        $this->ensureMember($member);

        $items = $this->validatedClassShirtItems($request);
        $existingOrder = $member->classShirtOrder;

        if (empty($items)) {
            $existingOrder?->delete();

            return redirect()
                ->route('backend.members.class-shirt-order', $member)
                ->with('status', 'Shirt order updated.');
        }

        $payment = $this->validatedClassShirtPayment($request);

        $member->classShirtOrder()->updateOrCreate([], [
            'items' => $items,
            'submitted_at' => $existingOrder?->submitted_at ?? now(),
            'payment_method' => $payment['payment_method'],
            'payment_account_last_five' => $payment['payment_account_last_five'],
            'payment_status' => $payment['payment_status'],
        ]);

        return redirect()
            ->route('backend.members.class-shirt-order', $member)
            ->with('status', 'Shirt order updated.');
    }

    public function update(Request $request, User $member): RedirectResponse
    {
        $this->ensureMember($member);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('users', 'name')->ignore($member)],
            'birthday' => ['nullable', 'date'],
            'mom_name' => ['nullable', 'string', 'max:120'],
            'mom_phone' => ['nullable', 'string', 'max:40'],
            'dad_name' => ['nullable', 'string', 'max:120'],
            'dad_phone' => ['nullable', 'string', 'max:40'],
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
            'password' => ['required', 'string', 'confirmed'],
        ]);

        $member->update([
            'login_password' => $validated['password'],
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

    private function validatedClassShirtItems(Request $request): array
    {
        $rawItems = collect($request->input('items', []))
            ->filter(fn (array $item): bool => filled($item['size'] ?? null) || filled($item['quantity'] ?? null))
            ->map(function (array $item): array {
                $item['size'] = ClassShirtOrder::normalizeSize($item['size'] ?? null);

                return $item;
            })
            ->values()
            ->all();

        if (empty($rawItems)) {
            return [];
        }

        validator(['items' => $rawItems], [
            'items' => ['required', 'array', 'min:1', 'max:50'],
            'items.*.category' => ['required', Rule::in(array_keys(ClassShirtOrder::CATEGORY_LABELS))],
            'items.*.size' => ['required', 'string', 'max:20'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ])->validate();

        $items = [];

        foreach ($rawItems as $index => $item) {
            if (! in_array($item['size'], ClassShirtOrder::SIZES[$item['category']], true)) {
                throw ValidationException::withMessages([
                    "items.{$index}.size" => 'Please choose a valid shirt size.',
                ]);
            }

            $items[] = [
                'category' => $item['category'],
                'size' => $item['size'],
                'quantity' => (int) $item['quantity'],
            ];
        }

        return $items;
    }

    private function validatedClassShirtPayment(Request $request): array
    {
        $validated = $request->validate([
            'payment_method' => ['required', Rule::in(array_keys(ClassShirtOrder::PAYMENT_METHOD_LABELS))],
            'payment_account_last_five' => [
                'nullable',
                Rule::requiredIf(fn (): bool =>
                    $request->input('payment_method') === ClassShirtOrder::PAYMENT_METHOD_TRANSFER
                    && $request->input('payment_status') !== ClassShirtOrder::PAYMENT_STATUS_UNPAID
                ),
                'digits:5',
            ],
            'payment_status' => ['required', Rule::in(array_keys(ClassShirtOrder::PAYMENT_STATUS_LABELS))],
        ], [
            'payment_account_last_five.required' => 'Transfer orders marked as pending or completed require the account last five digits.',
            'payment_account_last_five.digits' => 'Account last five digits must be exactly 5 numbers.',
        ]);

        return [
            'payment_method' => $validated['payment_method'],
            'payment_account_last_five' => $validated['payment_method'] === ClassShirtOrder::PAYMENT_METHOD_TRANSFER
                ? $validated['payment_account_last_five']
                : null,
            'payment_status' => $validated['payment_status'],
        ];
    }
}
