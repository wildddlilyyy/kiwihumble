<?php

namespace App\Http\Controllers\Member;

use App\Models\ClassShirtOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ClassShirtOrderController
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateOrder($request);

        $request->user('member')->classShirtOrders()->create([
            ...$validated,
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('member.dashboard', ['tab' => 'class-shirt'])
            ->with('status', '班服訂購已送出。');
    }

    public function update(Request $request, ClassShirtOrder $order): RedirectResponse
    {
        $this->ensureOwner($request, $order);

        $validated = $this->validateOrder($request);

        $order->update([
            ...$validated,
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('member.dashboard', ['tab' => 'class-shirt'])
            ->with('status', '班服訂購已更新。');
    }

    public function destroy(Request $request, ClassShirtOrder $order): RedirectResponse
    {
        $this->ensureOwner($request, $order);

        $order->delete();

        return redirect()
            ->route('member.dashboard', ['tab' => 'class-shirt'])
            ->with('status', '班服訂購已刪除。');
    }

    private function validateOrder(Request $request): array
    {
        $validated = $request->validate([
            'category' => ['required', Rule::in(array_keys(ClassShirtOrder::CATEGORY_LABELS))],
            'size' => ['required', 'string', 'max:20'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        if (! in_array($validated['size'], ClassShirtOrder::SIZES[$validated['category']], true)) {
            throw ValidationException::withMessages([
                'size' => '請選擇正確的班服尺寸。',
            ]);
        }

        return $validated;
    }

    private function ensureOwner(Request $request, ClassShirtOrder $order): void
    {
        abort_if($order->user_id !== $request->user('member')->id, 404);
    }
}
