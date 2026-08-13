<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function (): void {
            DB::table('class_shirt_orders')
                ->select(['id', 'items'])
                ->orderBy('id')
                ->get()
                ->each(function (object $order): void {
                    $items = json_decode($order->items, true, 512, JSON_THROW_ON_ERROR);
                    $changed = false;

                    foreach ($items as &$item) {
                        if (($item['category'] ?? null) === 'child' && ($item['size'] ?? null) === '#6') {
                            $item['size'] = '#8';
                            $changed = true;
                        }
                    }
                    unset($item);

                    if ($changed) {
                        DB::table('class_shirt_orders')
                            ->where('id', $order->id)
                            ->update([
                                'items' => json_encode($items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
                                'updated_at' => now(),
                            ]);
                    }
                });
        });
    }

    public function down(): void
    {
        // This conversion is intentionally irreversible: #8 may be an original
        // selection, so it cannot safely be changed back to the legacy #6.
    }
};
