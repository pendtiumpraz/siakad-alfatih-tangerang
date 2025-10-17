<?php

namespace Database\Seeders;

use App\Models\Kurikulum;
use App\Models\MataKuliah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kurikulums = Kurikulum::with('programStudi')->get();

        foreach ($kurikulums as $kurikulum) {
            $prodiKode = $kurikulum->programStudi->kode_prodi;

            if ($prodiKode === 'TI') {
                $mataKuliahs = [
                    [
                        'kode_mk' => 'TI101',
                        'nama_mk' => 'Algoritma dan Pemrograman',
                        'sks' => 3,
                        'semester' => 1,
                        'jenis' => 'wajib',
                        'deskripsi' => 'Mata kuliah dasar pemrograman dan algoritma',
                    ],
                    [
                        'kode_mk' => 'TI102',
                        'nama_mk' => 'Struktur Data',
                        'sks' => 3,
                        'semester' => 2,
                        'jenis' => 'wajib',
                        'deskripsi' => 'Mempelajari berbagai struktur data',
                    ],
                    [
                        'kode_mk' => 'TI103',
                        'nama_mk' => 'Basis Data',
                        'sks' => 3,
                        'semester' => 3,
                        'jenis' => 'wajib',
                        'deskripsi' => 'Pengenalan sistem basis data',
                    ],
                    [
                        'kode_mk' => 'TI104',
                        'nama_mk' => 'Pemrograman Web',
                        'sks' => 3,
                        'semester' => 4,
                        'jenis' => 'wajib',
                        'deskripsi' => 'Pengembangan aplikasi berbasis web',
                    ],
                    [
                        'kode_mk' => 'TI105',
                        'nama_mk' => 'Kecerdasan Buatan',
                        'sks' => 3,
                        'semester' => 5,
                        'jenis' => 'pilihan',
                        'deskripsi' => 'Pengenalan AI dan Machine Learning',
                    ],
                    [
                        'kode_mk' => 'TI106',
                        'nama_mk' => 'Keamanan Informasi',
                        'sks' => 3,
                        'semester' => 6,
                        'jenis' => 'pilihan',
                        'deskripsi' => 'Keamanan sistem dan jaringan',
                    ],
                ];
            } elseif ($prodiKode === 'SI') {
                $mataKuliahs = [
                    [
                        'kode_mk' => 'SI101',
                        'nama_mk' => 'Pengantar Sistem Informasi',
                        'sks' => 3,
                        'semester' => 1,
                        'jenis' => 'wajib',
                        'deskripsi' => 'Dasar-dasar sistem informasi',
                    ],
                    [
                        'kode_mk' => 'SI102',
                        'nama_mk' => 'Analisis dan Perancangan Sistem',
                        'sks' => 3,
                        'semester' => 2,
                        'jenis' => 'wajib',
                        'deskripsi' => 'Metodologi pengembangan sistem',
                    ],
                    [
                        'kode_mk' => 'SI103',
                        'nama_mk' => 'Manajemen Basis Data',
                        'sks' => 3,
                        'semester' => 3,
                        'jenis' => 'wajib',
                        'deskripsi' => 'Administrasi dan manajemen database',
                    ],
                    [
                        'kode_mk' => 'SI104',
                        'nama_mk' => 'Sistem Informasi Manajemen',
                        'sks' => 3,
                        'semester' => 4,
                        'jenis' => 'wajib',
                        'deskripsi' => 'Sistem informasi untuk manajemen',
                    ],
                    [
                        'kode_mk' => 'SI105',
                        'nama_mk' => 'E-Business',
                        'sks' => 3,
                        'semester' => 5,
                        'jenis' => 'pilihan',
                        'deskripsi' => 'Bisnis elektronik dan e-commerce',
                    ],
                ];
            } else { // MI (D3)
                $mataKuliahs = [
                    [
                        'kode_mk' => 'MI101',
                        'nama_mk' => 'Dasar Pemrograman',
                        'sks' => 3,
                        'semester' => 1,
                        'jenis' => 'wajib',
                        'deskripsi' => 'Pemrograman dasar',
                    ],
                    [
                        'kode_mk' => 'MI102',
                        'nama_mk' => 'Aplikasi Perkantoran',
                        'sks' => 2,
                        'semester' => 1,
                        'jenis' => 'wajib',
                        'deskripsi' => 'Microsoft Office dan aplikasi perkantoran',
                    ],
                    [
                        'kode_mk' => 'MI103',
                        'nama_mk' => 'Jaringan Komputer',
                        'sks' => 3,
                        'semester' => 2,
                        'jenis' => 'wajib',
                        'deskripsi' => 'Dasar jaringan komputer',
                    ],
                    [
                        'kode_mk' => 'MI104',
                        'nama_mk' => 'Web Design',
                        'sks' => 3,
                        'semester' => 3,
                        'jenis' => 'wajib',
                        'deskripsi' => 'Desain web dengan HTML, CSS, JavaScript',
                    ],
                    [
                        'kode_mk' => 'MI105',
                        'nama_mk' => 'Multimedia',
                        'sks' => 2,
                        'semester' => 4,
                        'jenis' => 'pilihan',
                        'deskripsi' => 'Pengolahan multimedia',
                    ],
                ];
            }

            foreach ($mataKuliahs as $mk) {
                MataKuliah::create(array_merge($mk, [
                    'kurikulum_id' => $kurikulum->id,
                ]));
            }
        }
    }
}
