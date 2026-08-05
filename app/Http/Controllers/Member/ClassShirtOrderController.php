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
        $items = $this->validateItems($request);

        $order = ClassShirtOrder::query()->updateOrCreate([
            'user_id' => $request->user('member')->id,
        ], [
            'items' => $items,
            'submitted_at' => now(),
        ]);

        return response()->json([
            'status' => '班服訂單已送出。',
            'items' => $order->items,
            'submitted_at' => $order->submitted_at?->timezone(config('app.timezone'))->format('Y-m-d H:i'),
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->user('member')->classShirtOrder()->delete();

        return response()->json([
            'status' => '班服訂單已清空。',
            'items' => [],
            'submitted_at' => null,
        ]);
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
            if (! in_array($item['size'], ClassShirtOrder::SIZES[$item['category']], true)) {
                throw ValidationException::withMessages([
                    "items.{$index}.size" => '請選擇正確的班服尺寸。',
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
}
