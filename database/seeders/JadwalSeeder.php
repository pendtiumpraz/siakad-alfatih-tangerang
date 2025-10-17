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

        $jadwals = [
            [
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $mataKuliahs->where('kode_mk', 'TI101')->first()->id,
                'dosen_id' => $dosens->first()->id,
                'ruangan_id' => $ruangans->where('kode_ruangan', 'R101')->first()->id,
                'hari' => 'Senin',
                'jam_mulai' => '08:00',
                'jam_selesai' => '10:30',
                'kelas' => 'A',
            ],
            [
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $mataKuliahs->where('kode_mk', 'TI102')->first()->id,
                'dosen_id' => $dosens->skip(1)->first()->id,
                'ruangan_id' => $ruangans->where('kode_ruangan', 'R102')->first()->id,
                'hari' => 'Senin',
                'jam_mulai' => '13:00',
                'jam_selesai' => '15:30',
                'kelas' => 'A',
            ],
            [
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $mataKuliahs->where('kode_mk', 'TI103')->first()->id,
                'dosen_id' => $dosens->skip(2)->first()->id,
                'ruangan_id' => $ruangans->where('kode_ruangan', 'LAB1')->first()->id,
                'hari' => 'Selasa',
                'jam_mulai' => '08:00',
                'jam_selesai' => '10:30',
                'kelas' => 'A',
            ],
            [
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $mataKuliahs->where('kode_mk', 'TI104')->first()->id,
                'dosen_id' => $dosens->first()->id,
                'ruangan_id' => $ruangans->where('kode_ruangan', 'LAB1')->first()->id,
                'hari' => 'Rabu',
                'jam_mulai' => '08:00',
                'jam_selesai' => '10:30',
                'kelas' => 'A',
            ],
            [
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $mataKuliahs->where('kode_mk', 'SI101')->first()->id,
                'dosen_id' => $dosens->skip(1)->first()->id,
                'ruangan_id' => $ruangans->where('kode_ruangan', 'R201')->first()->id,
                'hari' => 'Rabu',
                'jam_mulai' => '13:00',
                'jam_selesai' => '15:30',
                'kelas' => 'A',
            ],
            [
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $mataKuliahs->where('kode_mk', 'SI102')->first()->id,
                'dosen_id' => $dosens->skip(2)->first()->id,
                'ruangan_id' => $ruangans->where('kode_ruangan', 'R202')->first()->id,
                'hari' => 'Kamis',
                'jam_mulai' => '08:00',
                'jam_selesai' => '10:30',
                'kelas' => 'A',
            ],
            [
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $mataKuliahs->where('kode_mk', 'SI103')->first()->id,
                'dosen_id' => $dosens->first()->id,
                'ruangan_id' => $ruangans->where('kode_ruangan', 'LAB1')->first()->id,
                'hari' => 'Kamis',
                'jam_mulai' => '13:00',
                'jam_selesai' => '15:30',
                'kelas' => 'A',
            ],
            [
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $mataKuliahs->where('kode_mk', 'MI101')->first()->id,
                'dosen_id' => $dosens->skip(1)->first()->id,
                'ruangan_id' => $ruangans->where('kode_ruangan', 'LAB1')->first()->id,
                'hari' => 'Jumat',
                'jam_mulai' => '08:00',
                'jam_selesai' => '10:30',
                'kelas' => 'A',
            ],
            [
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $mataKuliahs->where('kode_mk', 'MI102')->first()->id,
                'dosen_id' => $dosens->skip(2)->first()->id,
                'ruangan_id' => $ruangans->where('kode_ruangan', 'LAB1')->first()->id,
                'hari' => 'Jumat',
                'jam_mulai' => '13:00',
                'jam_selesai' => '14:40',
                'kelas' => 'A',
            ],
            [
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $mataKuliahs->where('kode_mk', 'MI103')->first()->id,
                'dosen_id' => $dosens->first()->id,
                'ruangan_id' => $ruangans->where('kode_ruangan', 'R101')->first()->id,
                'hari' => 'Selasa',
                'jam_mulai' => '13:00',
                'jam_selesai' => '15:30',
                'kelas' => 'A',
            ],
        ];

        foreach ($jadwals as $jadwal) {
            Jadwal::create($jadwal);
        }
    }
}
