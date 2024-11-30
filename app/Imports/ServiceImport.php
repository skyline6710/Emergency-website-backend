<?php

namespace App\Imports;

use App\Models\Service;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ServiceImport implements ToCollection, WithHeadingRow
{
    /**
     * Handle the import of services from the Excel file.
     *
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Service::create([
                'provider'       => $row['اسم الجهة/الشخص'] ?? null,
                'serviceType'    => $row['نوع الخدمة'] ?? null,
                'coverdArea'     => $row['المناطق المتاحة للخدمة'] ?? null,
                'contactInfor'   => $row['وسيلة الاتصال'] ?? null,
                'avaliableTime'  => $row['الوقت المتاح للخدمة'] ?? null,
                'moreInfo'       => $row['تعليمات إضافية'] ?? null,
            ]);
        }
    }
}
