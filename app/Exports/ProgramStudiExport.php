<?php

namespace App\Exports;

use App\Models\ProgramStudi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ProgramStudiExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithMapping
{
    public function collection()
    {
        // Get all active program studi
        return ProgramStudi::where('is_active', true)
            ->orderBy('kode_prodi')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Kode Prodi',
            'Nama Program Studi',
            'Jenjang',
            'Akreditasi',
            'Status',
            'Deskripsi',
        ];
    }

    public function map($programStudi): array
    {
        return [
            $programStudi->kode_prodi,
            $programStudi->nama_prodi,
            $programStudi->jenjang,
            $programStudi->akreditasi ?? '-',
            $programStudi->is_active ? 'Aktif' : 'Non-Aktif',
            $programStudi->deskripsi ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text with background
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4A7C59']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,  // kode_prodi
            'B' => 45,  // nama_prodi
            'C' => 12,  // jenjang
            'D' => 15,  // akreditasi
            'E' => 12,  // status
            'F' => 50,  // deskripsi
        ];
    }
}
