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
                'nama' => 'Beasiswa 50% bagi Guru, Penghafal Quran, Yatim dan Dhuafa',
                'deskripsi' => 'Program beasiswa dengan potongan biaya kuliah 50% untuk Guru, Penghafal Quran, Anak Yatim dan Dhuafa',
                'biaya_pendaftaran' => 0,
                'is_active' => true,
                'kuota_total' => 100,
            ],
        ];

        foreach ($jalurSeleksis as $jalur) {
            JalurSeleksi::create($jalur);
        }
    }
}
