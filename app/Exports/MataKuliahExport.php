<?php

namespace App\Exports;

use App\Models\MataKuliah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class MataKuliahExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithMapping
{
    protected $programStudiId;

    public function __construct($programStudiId = null)
    {
        $this->programStudiId = $programStudiId;
    }

    public function collection()
    {
        $query = MataKuliah::with('kurikulum.programStudi');

        // Filter by program studi if provided
        if ($this->programStudiId) {
            $query->whereHas('kurikulum', function ($q) {
                $q->where('program_studi_id', $this->programStudiId);
            });
        }

        return $query->orderBy('kode_mk')->get();
    }

    public function headings(): array
    {
        return [
            'Kode Mata Kuliah',
            'Nama Mata Kuliah',
            'Program Studi',
            'Kurikulum',
            'SKS',
            'Semester',
            'Jenis',
            'Deskripsi',
        ];
    }

    public function map($mataKuliah): array
    {
        return [
            $mataKuliah->kode_mk,
            $mataKuliah->nama_mk,
            $mataKuliah->kurikulum->programStudi->kode_prodi ?? '-',
            $mataKuliah->kurikulum->nama_kurikulum ?? '-',
            $mataKuliah->sks,
            $mataKuliah->semester,
            $mataKuliah->jenis,
            $mataKuliah->deskripsi ?? '-',
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
                    'startColor' => ['rgb' => '2D5F4F']
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
            'A' => 20,  // kode_mk
            'B' => 40,  // nama_mk
            'C' => 18,  // program_studi
            'D' => 25,  // kurikulum
            'E' => 8,   // sks
            'F' => 10,  // semester
            'G' => 12,  // jenis
            'H' => 50,  // deskripsi
        ];
    }
}
