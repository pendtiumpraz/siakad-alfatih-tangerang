<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DosenTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        // Return sample data dosen - DATA BASIC SAJA
        // Username = NIDN (simple seperti mahasiswa pakai NIM)
        // Gelar bisa kosong (opsional), yang wajib: username (NIDN), email, nidn, nama_lengkap, kode_prodi
        // Kode_prodi bisa multiple: PAI-S1-L,MPI-S1-L atau single: PAI-S1-L
        // Kode_mk (mata kuliah) bisa multiple: PAI-S1-L-101,PAI-S1-L-102 atau kosong (assign manual nanti)
        // Lihat panduan di bawah untuk cek kode_prodi dan kode_mk yang tersedia di sistem
        return [
            // Dosen PAI S1 Luring (Prof/Dr) - dengan mata kuliah
            ['0101018901', '0101018901@staialfatih.ac.id', '', '0101018901', 'Ahmad Fauzi', 'Dr.', 'M.Pd.I', 'PAI-S1-L', 'PAI-S1-L-101,PAI-S1-L-102,PAI-S1-L-103'],
            ['0202019002', '0202019002@staialfatih.ac.id', '', '0202019002', 'Siti Nurhaliza', 'Dr.', 'M.A', 'PAI-S1-L', 'PAI-S1-L-201,PAI-S1-L-202'],
            ['0303018703', '0303018703@staialfatih.ac.id', '', '0303018703', 'Muhammad Yusuf', 'Prof. Dr.', 'M.A', 'PAI-S1-L,PAI-S1-D', 'PAI-S1-L-301,PAI-S1-D-301'],
            
            // Dosen MPI S1 Luring - dengan mata kuliah
            ['0404019104', '0404019104@staialfatih.ac.id', '', '0404019104', 'Abdullah Salim', '', 'S.Pd.I, M.Pd', 'MPI-S1-L', 'MPI-S1-L-101,MPI-S1-L-102'],
            ['0505019205', '0505019205@staialfatih.ac.id', '', '0505019205', 'Khadijah Azzahra', '', 'S.Ag, M.Pd.I', 'MPI-S1-L', 'MPI-S1-L-201'],
            
            // Dosen PGMI S1 Daring - tanpa mata kuliah (assign manual nanti)
            ['0808019008', '0808019008@staialfatih.ac.id', '', '0808019008', 'Hamzah Ibrahim', '', 'S.Pd, M.Pd', 'PGMI-S1-D', ''],
            ['0909019409', '0909019409@staialfatih.ac.id', '', '0909019409', 'Aisyah Zahra', '', 'S.Pd.I, M.Pd', 'PGMI-S1-D', ''],
            
            // Dosen HES S1 Luring - dengan mata kuliah
            ['1010018910', '1010018910@staialfatih.ac.id', '', '1010018910', 'Bilal Mustafa', '', 'S.H.I, M.E.Sy', 'HES-S1-L', 'HES-S1-L-101,HES-S1-L-201'],
            ['1111019211', '1111019211@staialfatih.ac.id', '', '1111019211', 'Maryam Safiya', '', 'S.Ag, M.E.I', 'HES-S1-L', 'HES-S1-L-301'],
            
            // Dosen Multiple Prodi (mengajar di beberapa prodi sekaligus)
            ['1212019512', '1212019512@staialfatih.ac.id', '', '1212019512', 'Zaid Hakim', '', 'S.Pd.I, M.Pd', 'PAI-S1-L,MPI-S1-L', 'PAI-S1-L-401,MPI-S1-L-401'],
            ['1313019613', '1313019613@staialfatih.ac.id', '', '1313019613', 'Naima Latifah', '', 'S.Th.I, M.A', 'PAI-S1-D,PGMI-S1-D', ''],
        ];
    }

    public function headings(): array
    {
        return [
            'username',
            'email',
            'no_telepon',
            'nidn',
            'nama_lengkap',
            'gelar_depan',
            'gelar_belakang',
            'kode_prodi',
            'kode_mk',
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
                    'startColor' => ['rgb' => 'D4AF37']
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
            'B' => 30,  // email
            'C' => 15,  // no_telepon
            'D' => 15,  // nidn
            'E' => 25,  // nama_lengkap
            'F' => 12,  // gelar_depan
            'G' => 15,  // gelar_belakang
            'H' => 20,  // kode_prodi
            'I' => 35,  // kode_mk
        ];
    }
}
