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
            ->select('class_shirt_orders.*')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Class Shirt Orders');
        $sheet->fromArray([
            ['NO', '會員 Name', '類別', '尺寸', '數量', '送出時間', '更新時間'],
        ]);

        $row = 2;
        $number = 1;

        foreach ($orders as $order) {
            foreach ($order->items ?? [] as $item) {
                $sheet->fromArray([[
                    $number,
                    $order->user?->name,
                    ClassShirtOrder::categoryLabel($item['category'] ?? ''),
                    $item['size'] ?? '',
                    $item['quantity'] ?? '',
                    $order->submitted_at?->timezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                    $order->updated_at?->timezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                ]], null, 'A'.$row);

                $row++;
                $number++;
            }
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
