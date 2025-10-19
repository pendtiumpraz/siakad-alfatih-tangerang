<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JalurSeleksi;

class JalurSeleksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jalurSeleksis = [
            [
                'nama' => 'Jalur Prestasi',
                'deskripsi' => 'Jalur seleksi untuk calon mahasiswa yang memiliki prestasi akademik atau non-akademik tingkat nasional atau internasional',
                'biaya_pendaftaran' => 0,
                'is_active' => true,
                'kuota_total' => 50,
            ],
            [
                'nama' => 'Jalur Tes',
                'deskripsi' => 'Jalur seleksi melalui ujian tertulis dan/atau tes kemampuan yang diselenggarakan oleh kampus',
                'biaya_pendaftaran' => 300000,
                'is_active' => true,
                'kuota_total' => 200,
            ],
            [
                'nama' => 'Jalur Undangan',
                'deskripsi' => 'Jalur seleksi untuk calon mahasiswa yang diundang berdasarkan prestasi akademik di sekolah',
                'biaya_pendaftaran' => 0,
                'is_active' => true,
                'kuota_total' => 100,
            ],
            [
                'nama' => 'Jalur Reguler',
                'deskripsi' => 'Jalur seleksi reguler untuk umum dengan persyaratan standar',
                'biaya_pendaftaran' => 250000,
                'is_active' => true,
                'kuota_total' => 300,
            ],
            [
                'nama' => 'Jalur Beasiswa',
                'deskripsi' => 'Jalur seleksi untuk calon mahasiswa yang memiliki potensi akademik tinggi namun terkendala ekonomi',
                'biaya_pendaftaran' => 0,
                'is_active' => true,
                'kuota_total' => 30,
            ],
            [
                'nama' => 'Jalur Mandiri',
                'deskripsi' => 'Jalur seleksi mandiri dengan biaya pendidikan mandiri',
                'biaya_pendaftaran' => 500000,
                'is_active' => true,
                'kuota_total' => 150,
            ],
        ];

        foreach ($jalurSeleksis as $jalur) {
            JalurSeleksi::create($jalur);
        }
    }
}
