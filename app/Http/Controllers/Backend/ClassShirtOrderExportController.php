<?php

namespace App\Http\Controllers\Backend;

use App\Models\ClassShirtOrder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClassShirtOrderExportController
{
    public function __invoke(): StreamedResponse
    {
        $orders = ClassShirtOrder::query()
            ->with('user')
            ->join('users', 'users.id', '=', 'class_shirt_orders.user_id')
            ->orderBy('users.name')
            ->orderBy('class_shirt_orders.id')
            ->select('class_shirt_orders.*')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Class Shirt Orders');
        $sheet->fromArray([
            ['NO', '會員 Name', '類別', '尺寸', '數量', '送出時間', '更新時間'],
        ]);

        foreach ($orders as $index => $order) {
            $sheet->fromArray([[
                $index + 1,
                $order->user?->name,
                $order->categoryLabel(),
                $order->size,
                $order->quantity,
                $order->submitted_at?->timezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                $order->updated_at?->timezone(config('app.timezone'))->format('Y-m-d H:i:s'),
            ]], null, 'A'.($index + 2));
        }

        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return response()->streamDownload(function () use ($spreadsheet): void {
            (new Xlsx($spreadsheet))->save('php://output');
        }, 'class-shirt-orders.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
