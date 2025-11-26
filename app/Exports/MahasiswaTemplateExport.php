<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class MahasiswaTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        // Return sample data mahasiswa - DATA BASIC SAJA
        // Detail seperti tempat lahir, tanggal lahir, alamat, no HP biarkan kosong
        // Nanti mahasiswa update sendiri via profile
        return [
            // Mahasiswa Semester 5 (Angkatan 2022) - PAI S1 Luring - AKTIF
            ['2022001', '2022001@staialfatih.ac.id', '', '2022001', 'Ahmad Zaki Mubarak', 'PAI-S1-L', '2022', '', '', 'L', '', 'aktif', '', ''],
            ['2022002', '2022002@staialfatih.ac.id', '', '2022002', 'Siti Aisyah Nurjanah', 'PAI-S1-L', '2022', '', '', 'P', '', 'aktif', '', ''],
            ['2022003', '2022003@staialfatih.ac.id', '', '2022003', 'Muhammad Iqbal Ramadhan', 'PAI-S1-L', '2022', '', '', 'L', '', 'aktif', '', ''],
            
            // Mahasiswa Semester 5 (Angkatan 2022) - MPI S1 Luring - AKTIF
            ['2022004', '2022004@staialfatih.ac.id', '', '2022004', 'Fatimah Az-Zahra', 'MPI-S1-L', '2022', '', '', 'P', '', 'aktif', '', ''],
            ['2022005', '2022005@staialfatih.ac.id', '', '2022005', 'Umar Abdullah Faruq', 'MPI-S1-L', '2022', '', '', 'L', '', 'aktif', '', ''],
            
            // Mahasiswa Semester 5 (Angkatan 2022) - PGMI S1 Daring - AKTIF
            ['2022006', '2022006@staialfatih.ac.id', '', '2022006', 'Khadijah Husna', 'PGMI-S1-D', '2022', '', '', 'P', '', 'aktif', '', ''],
            ['2022007', '2022007@staialfatih.ac.id', '', '2022007', 'Ali Hasan Maulana', 'PGMI-S1-D', '2022', '', '', 'L', '', 'aktif', '', ''],
            
            // Mahasiswa Semester 5 (Angkatan 2022) - HES S1 Luring - AKTIF
            ['2022008', '2022008@staialfatih.ac.id', '', '2022008', 'Mariam Salma Amalia', 'HES-S1-L', '2022', '', '', 'P', '', 'aktif', '', ''],
            ['2022009', '2022009@staialfatih.ac.id', '', '2022009', 'Hamzah Yusuf Habibi', 'HES-S1-L', '2022', '', '', 'L', '', 'aktif', '', ''],
            ['2022010', '2022010@staialfatih.ac.id', '', '2022010', 'Zaynab Nadia Putri', 'HES-S1-L', '2022', '', '', 'P', '', 'aktif', '', ''],
            
            // Contoh mahasiswa dengan status CUTI
            ['2022011', '2022011@staialfatih.ac.id', '', '2022011', 'Ibrahim Malik Firdaus', 'PAI-S1-L', '2022', '', '', 'L', '', 'cuti', '', ''],
            
            // Contoh mahasiswa yang LULUS (otomatis non-aktif, tidak bisa login)
            ['2020001', '2020001@staialfatih.ac.id', '', '2020001', 'Hasan Abdullah Alumni', 'PAI-S1-L', '2020', '', '', 'L', '', 'lulus', '2024-08-15', ''],
            
            // Contoh mahasiswa yang DROPOUT (otomatis non-aktif, tidak bisa login)
            ['2021001', '2021001@staialfatih.ac.id', '', '2021001', 'Ahmad Zainuddin', 'MPI-S1-L', '2021', '', '', 'L', '', 'dropout', '', '2023-12-01'],
        ];
    }

    public function headings(): array
    {
        return [
            'username',
            'email',
            'no_telepon',
            'nim',
            'nama_lengkap',
            'kode_prodi',
            'angkatan',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'alamat',
            'status',
            'tanggal_lulus',
            'tanggal_dropout',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => [
                'font' => ['bold' => true, 'size' => 12],
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
            'A' => 15,  // username
            'B' => 25,  // email
            'C' => 15,  // no_telepon
            'D' => 12,  // nim
            'E' => 25,  // nama_lengkap
            'F' => 12,  // kode_prodi
            'G' => 10,  // angkatan
            'H' => 15,  // tempat_lahir
            'I' => 15,  // tanggal_lahir
            'J' => 15,  // jenis_kelamin
            'K' => 30,  // alamat
            'L' => 12,  // status
            'M' => 15,  // tanggal_lulus
            'N' => 15,  // tanggal_dropout
        ];
    }
}
