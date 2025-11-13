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
            // Ruangan Offline (Fisik)
            [
                'kode_ruangan' => 'R101',
                'nama_ruangan' => 'Ruang Kuliah 101',
                'kapasitas' => 40,
                'jenis' => 'offline',
                'fasilitas' => 'Proyektor, AC, Whiteboard',
                'is_available' => true,
            ],
            [
                'kode_ruangan' => 'R102',
                'nama_ruangan' => 'Ruang Kuliah 102',
                'kapasitas' => 40,
                'jenis' => 'offline',
                'fasilitas' => 'Proyektor, AC, Whiteboard',
                'is_available' => true,
            ],
            [
                'kode_ruangan' => 'R201',
                'nama_ruangan' => 'Ruang Kuliah 201',
                'kapasitas' => 50,
                'jenis' => 'offline',
                'fasilitas' => 'Proyektor, AC, Whiteboard, Sound System',
                'is_available' => true,
            ],
            [
                'kode_ruangan' => 'R202',
                'nama_ruangan' => 'Ruang Kuliah 202',
                'kapasitas' => 35,
                'jenis' => 'offline',
                'fasilitas' => 'Proyektor, AC, Whiteboard',
                'is_available' => true,
            ],
            [
                'kode_ruangan' => 'LAB1',
                'nama_ruangan' => 'Laboratorium Komputer 1',
                'kapasitas' => 30,
                'jenis' => 'offline',
                'fasilitas' => '30 Unit Komputer, Proyektor, AC, Whiteboard',
                'is_available' => true,
            ],
            
            // Ruangan Online (Daring)
            [
                'kode_ruangan' => 'ONLINE-1',
                'nama_ruangan' => 'Ruang Daring 1',
                'kapasitas' => 100, // Virtual - bisa lebih besar
                'jenis' => 'online',
                'fasilitas' => 'Google Meet, Zoom, Microsoft Teams',
                'is_available' => true,
            ],
            [
                'kode_ruangan' => 'ONLINE-2',
                'nama_ruangan' => 'Ruang Daring 2',
                'kapasitas' => 100,
                'jenis' => 'online',
                'fasilitas' => 'Google Meet, Zoom, Microsoft Teams',
                'is_available' => true,
            ],
            [
                'kode_ruangan' => 'ONLINE-3',
                'nama_ruangan' => 'Ruang Daring 3',
                'kapasitas' => 100,
                'jenis' => 'online',
                'fasilitas' => 'Google Meet, Zoom, Microsoft Teams',
                'is_available' => true,
            ],
            [
                'kode_ruangan' => 'ONLINE-4',
                'nama_ruangan' => 'Ruang Daring 4',
                'kapasitas' => 100,
                'jenis' => 'online',
                'fasilitas' => 'Google Meet, Zoom, Microsoft Teams',
                'is_available' => true,
            ],
            [
                'kode_ruangan' => 'ONLINE-5',
                'nama_ruangan' => 'Ruang Daring 5',
                'kapasitas' => 100,
                'jenis' => 'online',
                'fasilitas' => 'Google Meet, Zoom, Microsoft Teams',
                'is_available' => true,
            ],
        ];

        foreach ($ruangans as $ruangan) {
            Ruangan::firstOrCreate(
                ['kode_ruangan' => $ruangan['kode_ruangan']], 
                $ruangan
            );
        }

        $this->command->info('✓ Ruangan seeded: 5 offline + 5 online rooms');
    }
}
