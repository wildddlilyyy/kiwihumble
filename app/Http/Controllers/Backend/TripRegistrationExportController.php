<?php

namespace App\Http\Controllers\Backend;

use App\Models\TripRegistration;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TripRegistrationExportController
{
    public function __invoke(): StreamedResponse
    {
        $registrations = TripRegistration::query()
            ->with('user')
            ->join('users', 'users.id', '=', 'trip_registrations.user_id')
            ->orderBy('users.name')
            ->select('trip_registrations.*')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Trip Registrations');
        $sheet->fromArray([[
            'NO',
            'Name',
            '大人',
            '小孩',
            '小孩年齡',
            '房間',
            '房型',
            '送出時間',
            '更新時間',
        ]]);

        $row = 2;
        $number = 1;

        foreach ($registrations as $registration) {
            $childAges = collect($registration->child_ages ?? [])
                ->map(fn ($age) => $age.'Y')
                ->join(', ');
            $roomTypes = collect($registration->room_types ?? [])
                ->map(fn ($type) => TripRegistration::roomTypeLabel($type))
                ->join('、');

            $sheet->fromArray([[
                $number,
                $registration->user?->name,
                $registration->adults_count,
                $registration->children_count,
                $childAges,
                $registration->room_count,
                $roomTypes,
                $registration->submitted_at?->timezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                $registration->updated_at?->timezone(config('app.timezone'))->format('Y-m-d H:i:s'),
            ]], null, 'A'.$row);

            $row++;
            $number++;
        }

        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return response()->streamDownload(function () use ($spreadsheet): void {
            (new Xlsx($spreadsheet))->save('php://output');
        }, 'trip-registrations.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
