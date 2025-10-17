<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ruangans = [
            [
                'kode_ruangan' => 'R101',
                'nama_ruangan' => 'Ruang Kuliah 101',
                'kapasitas' => 40,
                'fasilitas' => 'Proyektor, AC, Whiteboard',
            ],
            [
                'kode_ruangan' => 'R102',
                'nama_ruangan' => 'Ruang Kuliah 102',
                'kapasitas' => 40,
                'fasilitas' => 'Proyektor, AC, Whiteboard',
            ],
            [
                'kode_ruangan' => 'R201',
                'nama_ruangan' => 'Ruang Kuliah 201',
                'kapasitas' => 50,
                'fasilitas' => 'Proyektor, AC, Whiteboard, Sound System',
            ],
            [
                'kode_ruangan' => 'R202',
                'nama_ruangan' => 'Ruang Kuliah 202',
                'kapasitas' => 35,
                'fasilitas' => 'Proyektor, AC, Whiteboard',
            ],
            [
                'kode_ruangan' => 'LAB1',
                'nama_ruangan' => 'Laboratorium Komputer 1',
                'kapasitas' => 30,
                'fasilitas' => '30 Unit Komputer, Proyektor, AC, Whiteboard',
            ],
        ];

        foreach ($ruangans as $ruangan) {
            Ruangan::create($ruangan);
        }
    }
}
