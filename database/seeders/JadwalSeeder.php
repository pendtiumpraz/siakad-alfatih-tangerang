<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\MataKuliah;
use App\Models\Ruangan;
use App\Models\Semester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $semester = Semester::where('is_active', true)->first();
        $mataKuliahs = MataKuliah::all();
        $dosens = Dosen::all();
        $ruangans = Ruangan::all();

        // Helper function to find mata kuliah by code pattern (supports new -K{id} suffix)
        $findMk = function($pattern) use ($mataKuliahs) {
            $mk = $mataKuliahs->first(function($item) use ($pattern) {
                return str_starts_with($item->kode_mk, $pattern);
            });
            return $mk ? $mk->id : null;
        };

        $jadwals = [
            [
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $findMk('TI101'),
                'dosen_id' => $dosens->first()->id ?? null,
                'ruangan_id' => $ruangans->where('kode_ruangan', 'R101')->first()->id ?? null,
                'hari' => 'Senin',
                'jam_mulai' => '08:00',
                'jam_selesai' => '10:30',
                'kelas' => 'A',
            ],
            [
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $findMk('TI102'),
                'dosen_id' => $dosens->skip(1)->first()->id ?? null,
                'ruangan_id' => $ruangans->where('kode_ruangan', 'R102')->first()->id ?? null,
                'hari' => 'Senin',
                'jam_mulai' => '13:00',
                'jam_selesai' => '15:30',
                'kelas' => 'A',
            ],
            [
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $findMk('TI103'),
                'dosen_id' => $dosens->skip(2)->first()->id ?? null,
                'ruangan_id' => $ruangans->where('kode_ruangan', 'LAB1')->first()->id ?? null,
                'hari' => 'Selasa',
                'jam_mulai' => '08:00',
                'jam_selesai' => '10:30',
                'kelas' => 'A',
            ],
            [
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $findMk('TI104'),
                'dosen_id' => $dosens->first()->id ?? null,
                'ruangan_id' => $ruangans->where('kode_ruangan', 'LAB1')->first()->id ?? null,
                'hari' => 'Rabu',
                'jam_mulai' => '08:00',
                'jam_selesai' => '10:30',
                'kelas' => 'A',
            ],
            [
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $findMk('SI101'),
                'dosen_id' => $dosens->skip(1)->first()->id ?? null,
                'ruangan_id' => $ruangans->where('kode_ruangan', 'R201')->first()->id ?? null,
                'hari' => 'Rabu',
                'jam_mulai' => '13:00',
                'jam_selesai' => '15:30',
                'kelas' => 'A',
            ],
            [
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $findMk('SI102'),
                'dosen_id' => $dosens->skip(2)->first()->id ?? null,
                'ruangan_id' => $ruangans->where('kode_ruangan', 'R202')->first()->id ?? null,
                'hari' => 'Kamis',
                'jam_mulai' => '08:00',
                'jam_selesai' => '10:30',
                'kelas' => 'A',
            ],
            [
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $findMk('SI103'),
                'dosen_id' => $dosens->first()->id ?? null,
                'ruangan_id' => $ruangans->where('kode_ruangan', 'LAB1')->first()->id ?? null,
                'hari' => 'Kamis',
                'jam_mulai' => '13:00',
                'jam_selesai' => '15:30',
                'kelas' => 'A',
            ],
            [
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $findMk('MI101'),
                'dosen_id' => $dosens->skip(1)->first()->id ?? null,
                'ruangan_id' => $ruangans->where('kode_ruangan', 'LAB1')->first()->id ?? null,
                'hari' => 'Jumat',
                'jam_mulai' => '08:00',
                'jam_selesai' => '10:30',
                'kelas' => 'A',
            ],
            [
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $findMk('MI102'),
                'dosen_id' => $dosens->skip(2)->first()->id ?? null,
                'ruangan_id' => $ruangans->where('kode_ruangan', 'LAB1')->first()->id ?? null,
                'hari' => 'Jumat',
                'jam_mulai' => '13:00',
                'jam_selesai' => '14:40',
                'kelas' => 'A',
            ],
            [
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $findMk('MI103'),
                'dosen_id' => $dosens->first()->id ?? null,
                'ruangan_id' => $ruangans->where('kode_ruangan', 'R101')->first()->id ?? null,
                'hari' => 'Selasa',
                'jam_mulai' => '13:00',
                'jam_selesai' => '15:30',
                'kelas' => 'A',
            ],
        ];

        foreach ($jadwals as $jadwal) {
            // Only create if mata_kuliah_id exists
            if ($jadwal['mata_kuliah_id']) {
                Jadwal::create($jadwal);
            }
        }
    }
}
