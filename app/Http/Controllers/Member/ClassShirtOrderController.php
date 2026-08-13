<?php

namespace App\Http\Controllers\Member;

use App\Models\ClassShirtOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ClassShirtOrderController
{
    public function store(Request $request): JsonResponse
    {
        if ($request->user('member')->classShirtOrder()->exists()) {
            return response()->json([
                'message' => '班服訂單已送出，如需修改請聯繫管理者。',
            ], 403);
        }

        $items = $this->validateItems($request);
        $payment = $this->validatePayment($request);

        $order = ClassShirtOrder::query()->create([
            'user_id' => $request->user('member')->id,
            'items' => $items,
            'submitted_at' => now(),
            'payment_method' => $payment['payment_method'],
            'payment_account_last_five' => $payment['payment_account_last_five'],
            'payment_status' => ClassShirtOrder::PAYMENT_STATUS_PENDING,
        ]);

        return response()->json([
            'status' => '班服訂單已送出，付款待確認。',
            'items' => $order->items,
            'submitted_at' => $order->submitted_at?->timezone(config('app.timezone'))->format('Y-m-d H:i'),
            'payment_method' => $order->payment_method,
            'payment_method_label' => $order->payment_method_label,
            'payment_account_last_five' => $order->payment_account_last_five,
            'payment_status' => $order->payment_status,
            'payment_status_label' => $order->payment_status_label,
            'total_quantity' => $order->totalQuantity(),
            'total_amount' => $order->totalAmount(),
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        return response()->json([
            'message' => '班服訂單已送出，如需修改請聯繫管理者。',
        ], 403);
    }

    public function updatePayment(Request $request)
    {
        $order = $request->user('member')->classShirtOrder;

        abort_if(! $order, 404);

        $payment = $this->validatePayment($request);

        $order->update([
            'payment_method' => $payment['payment_method'],
            'payment_account_last_five' => $payment['payment_account_last_five'],
        ]);

        return redirect()
            ->route('member.dashboard', ['tab' => 'class-shirt'])
            ->with('status', '付款資訊已更新。');
    }

    private function validateItems(Request $request): array
    {
        $request->validate([
            'items' => ['required', 'array', 'min:1', 'max:50'],
            'items.*.category' => ['required', Rule::in(array_keys(ClassShirtOrder::CATEGORY_LABELS))],
            'items.*.size' => ['required', 'string', 'max:20'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $items = [];

        foreach ($request->input('items') as $index => $item) {
            $item['size'] = ClassShirtOrder::normalizeSize($item['size'] ?? null);

            if (! in_array($item['size'], ClassShirtOrder::SIZES[$item['category']], true)) {
                throw ValidationException::withMessages([
                    "items.{$index}.size" => '請選擇有效的班服尺寸。',
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

    private function validatePayment(Request $request): array
    {
        $validated = $request->validate([
            'payment_method' => ['required', Rule::in(array_keys(ClassShirtOrder::PAYMENT_METHOD_LABELS))],
            'payment_account_last_five' => [
                'nullable',
                'required_if:payment_method,'.ClassShirtOrder::PAYMENT_METHOD_TRANSFER,
                'digits:5',
            ],
        ], [
            'payment_account_last_five.required_if' => '選擇匯款時，請填寫帳號末五碼。',
            'payment_account_last_five.digits' => '帳號末五碼需為 5 碼數字。',
        ]);

        return [
            'payment_method' => $validated['payment_method'],
            'payment_account_last_five' => $validated['payment_method'] === ClassShirtOrder::PAYMENT_METHOD_TRANSFER
                ? $validated['payment_account_last_five']
                : null,
        ];
    }
}
