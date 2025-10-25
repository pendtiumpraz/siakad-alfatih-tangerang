<?php

namespace Database\Seeders;

use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaiAlfatihMataKuliahS1Seeder extends Seeder
{
    /**
     * STAI AL-FATIH S1 Mata Kuliah Seeder
     * Data from official website: https://staialfatih.or.id/
     * Last updated: 2025-10-25
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // Get all S1 programs
            $s1Programs = ProgramStudi::where('jenjang', 'S1')
                ->whereIn('kode_prodi', ['PAI-S1-L', 'PAI-S1-D', 'MPI-S1-L', 'MPI-S1-D',
                                         'HES-S1-L', 'HES-S1-D', 'PGMI-S1-L', 'PGMI-S1-D'])
                ->get();

            foreach ($s1Programs as $prodi) {
                // Create or get kurikulum
                $kurikulum = Kurikulum::firstOrCreate(
                    ['program_studi_id' => $prodi->id],
                    [
                        'nama_kurikulum' => "Kurikulum {$prodi->nama_prodi} 2024",
                        'tahun_mulai' => 2024,
                        'tahun_selesai' => null,
                        'is_active' => true,
                        'total_sks' => 148,
                    ]
                );

                // Determine which mata kuliah to use based on program
                $baseProdi = explode('-', $prodi->kode_prodi)[0]; // PAI, MPI, HES, or PGMI
                $mataKuliahs = $this->getMataKuliahByProdi($baseProdi, $prodi->kode_prodi);

                // Insert mata kuliah
                foreach ($mataKuliahs as $mk) {
                    $exists = MataKuliah::where('kode_mk', $mk['kode_mk'])
                        ->where('kurikulum_id', $kurikulum->id)
                        ->exists();

                    if (!$exists) {
                        MataKuliah::create(array_merge($mk, [
                            'kurikulum_id' => $kurikulum->id,
                        ]));
                    }
                }

                $courseCount = count($mataKuliahs);
                $this->command->info("✓ Seeded: {$prodi->nama_prodi} ({$courseCount} courses)");
            }

            DB::commit();
            $this->command->info("\n✓ S1 Mata Kuliah seeder completed!");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('✗ Error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function getMataKuliahByProdi($baseProdi, $fullKodeProdi)
    {
        switch ($baseProdi) {
            case 'PAI':
                return $this->getPAIMataKuliah($fullKodeProdi);
            case 'MPI':
                return $this->getMPIMataKuliah($fullKodeProdi);
            case 'HES':
                return $this->getHESMataKuliah($fullKodeProdi);
            case 'PGMI':
                return $this->getPGMIMataKuliah($fullKodeProdi);
            default:
                return [];
        }
    }

    private function getPAIMataKuliah($kodeProdi)
    {
        $suffix = str_contains($kodeProdi, '-D') ? 'D' : 'L';

        return [
            // Semester 1 (18 SKS)
            ['kode_mk' => "PAI-1-001-{$suffix}", 'nama_mk' => 'Pendidikan Pancasila dan Kewarganegaraan', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Pendidikan Pancasila dan Kewarganegaraan'],
            ['kode_mk' => "PAI-1-002-{$suffix}", 'nama_mk' => 'Bahasa Inggris', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Inggris'],
            ['kode_mk' => "PAI-1-003-{$suffix}", 'nama_mk' => 'Aqidah', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Aqidah'],
            ['kode_mk' => "PAI-1-004-{$suffix}", 'nama_mk' => 'Fiqih Ibadah', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Fiqih Ibadah'],
            ['kode_mk' => "PAI-1-005-{$suffix}", 'nama_mk' => 'Bahasa Arab', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab'],
            ['kode_mk' => "PAI-1-006-{$suffix}", 'nama_mk' => 'Sirah Nabawiyah', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Sirah Nabawiyah'],
            ['kode_mk' => "PAI-1-007-{$suffix}", 'nama_mk' => "Tahsin & Tahfidz Alqur'an", 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => "Tahsin & Tahfidz Alqur'an"],
            ['kode_mk' => "PAI-1-008-{$suffix}", 'nama_mk' => 'Ushul Tafshir', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Ushul Tafshir'],
            ['kode_mk' => "PAI-1-009-{$suffix}", 'nama_mk' => 'Adab Akhlak', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Adab Akhlak'],

            // Semester 2 (19 SKS)
            ['kode_mk' => "PAI-2-001-{$suffix}", 'nama_mk' => 'Aqidah', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Aqidah'],
            ['kode_mk' => "PAI-2-002-{$suffix}", 'nama_mk' => "Tahsin & Tahfidz Alqur'an", 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => "Tahsin & Tahfidz Alqur'an"],
            ['kode_mk' => "PAI-2-003-{$suffix}", 'nama_mk' => 'Bahasa Arab', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab'],
            ['kode_mk' => "PAI-2-004-{$suffix}", 'nama_mk' => 'Bahasa Indonesia', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Indonesia'],
            ['kode_mk' => "PAI-2-005-{$suffix}", 'nama_mk' => 'Psikologi Pendidikan Islam', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Psikologi Pendidikan Islam'],
            ['kode_mk' => "PAI-2-006-{$suffix}", 'nama_mk' => 'Fiqih Munakahat dan Warist', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Fiqih Munakahat dan Warist'],
            ['kode_mk' => "PAI-2-007-{$suffix}", 'nama_mk' => "Ulumul Qur'an", 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => "Ulumul Qur'an"],
            ['kode_mk' => "PAI-2-008-{$suffix}", 'nama_mk' => 'Tsaqofah Islamiyah', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Tsaqofah Islamiyah'],
            ['kode_mk' => "PAI-2-009-{$suffix}", 'nama_mk' => 'Bahasa Inggris', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Inggris'],

            // Semester 3 (20 SKS)
            ['kode_mk' => "PAI-3-001-{$suffix}", 'nama_mk' => 'Hadist', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Hadist'],
            ['kode_mk' => "PAI-3-002-{$suffix}", 'nama_mk' => 'Tafsir', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Tafsir'],
            ['kode_mk' => "PAI-3-003-{$suffix}", 'nama_mk' => 'Komunikasi Pendidikan Islam', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Komunikasi Pendidikan Islam'],
            ['kode_mk' => "PAI-3-004-{$suffix}", 'nama_mk' => 'Supervisi Pendidikan Islam', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Supervisi Pendidikan Islam'],
            ['kode_mk' => "PAI-3-005-{$suffix}", 'nama_mk' => 'Bahasa Arab', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab'],
            ['kode_mk' => "PAI-3-006-{$suffix}", 'nama_mk' => 'Literasi Digital Islam', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Literasi Digital Islam'],
            ['kode_mk' => "PAI-3-007-{$suffix}", 'nama_mk' => 'Metodologi Studi Islam', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi Studi Islam'],
            ['kode_mk' => "PAI-3-008-{$suffix}", 'nama_mk' => 'Sejarah Pendidikan Islam', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Sejarah Pendidikan Islam'],
            ['kode_mk' => "PAI-3-009-{$suffix}", 'nama_mk' => 'Fiqih Dakwah', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Fiqih Dakwah'],
            ['kode_mk' => "PAI-3-010-{$suffix}", 'nama_mk' => 'Tahsin & Tahfidzh Alquran', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Tahsin & Tahfidzh Alquran'],

            // Semester 4 (23 SKS)
            ['kode_mk' => "PAI-4-001-{$suffix}", 'nama_mk' => 'Media Pengajaran Berbasis Aplikasi', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Media Pengajaran Berbasis Aplikasi'],
            ['kode_mk' => "PAI-4-002-{$suffix}", 'nama_mk' => 'Tafsir Tarbawi', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Tafsir Tarbawi'],
            ['kode_mk' => "PAI-4-003-{$suffix}", 'nama_mk' => 'Metode Penelitian Kuantitatif', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Metode Penelitian Kuantitatif'],
            ['kode_mk' => "PAI-4-004-{$suffix}", 'nama_mk' => 'Aqidah', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Aqidah'],
            ['kode_mk' => "PAI-4-005-{$suffix}", 'nama_mk' => 'Bahasa Arab', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab'],
            ['kode_mk' => "PAI-4-006-{$suffix}", 'nama_mk' => 'Tahsin dan Tahfidz Alquran', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Tahsin dan Tahfidz Alquran'],
            ['kode_mk' => "PAI-4-007-{$suffix}", 'nama_mk' => 'Manajemen Pengelolaan SDM', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen Pengelolaan SDM'],
            ['kode_mk' => "PAI-4-008-{$suffix}", 'nama_mk' => 'Fiqih Muyassar', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Fiqih Muyassar'],
            ['kode_mk' => "PAI-4-009-{$suffix}", 'nama_mk' => 'Hadist', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Hadist'],
            ['kode_mk' => "PAI-4-010-{$suffix}", 'nama_mk' => 'Filsafat Pendidikan Islam dalam Timbangan Syariat', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Filsafat Pendidikan Islam dalam Timbangan Syariat'],
            ['kode_mk' => "PAI-4-011-{$suffix}", 'nama_mk' => 'Perencanaan Pembelajaran Berbasis IT', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Perencanaan Pembelajaran Berbasis IT'],

            // Semester 5 (16 SKS)
            ['kode_mk' => "PAI-5-001-{$suffix}", 'nama_mk' => 'Micro Teaching', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Micro Teaching'],
            ['kode_mk' => "PAI-5-002-{$suffix}", 'nama_mk' => 'Metode Penelitian Kualitatif', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Metode Penelitian Kualitatif'],
            ['kode_mk' => "PAI-5-003-{$suffix}", 'nama_mk' => 'Administrasi Pendidikan Islam berbasis IT', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Administrasi Pendidikan Islam berbasis IT'],
            ['kode_mk' => "PAI-5-004-{$suffix}", 'nama_mk' => 'Bahasa Arab', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab'],
            ['kode_mk' => "PAI-5-005-{$suffix}", 'nama_mk' => 'Tahsin dan Tahfidz Alquran', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Tahsin dan Tahfidz Alquran'],
            ['kode_mk' => "PAI-5-006-{$suffix}", 'nama_mk' => 'Fiqih', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Fiqih'],
            ['kode_mk' => "PAI-5-007-{$suffix}", 'nama_mk' => 'Pengembangan Kurikulum', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Pengembangan Kurikulum'],
            ['kode_mk' => "PAI-5-008-{$suffix}", 'nama_mk' => 'Statistik Pendidikan', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Statistik Pendidikan'],

            // Semester 6 (17 SKS)
            ['kode_mk' => "PAI-6-001-{$suffix}", 'nama_mk' => 'Manajemen berbasis Sekolah', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen berbasis Sekolah'],
            ['kode_mk' => "PAI-6-002-{$suffix}", 'nama_mk' => 'Bahasa Arab', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab'],
            ['kode_mk' => "PAI-6-003-{$suffix}", 'nama_mk' => 'Tahsin dan Tahfidz Alquran', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Tahsin dan Tahfidz Alquran'],
            ['kode_mk' => "PAI-6-004-{$suffix}", 'nama_mk' => 'Aqidah', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Aqidah'],
            ['kode_mk' => "PAI-6-005-{$suffix}", 'nama_mk' => 'KKN', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'KKN'],
            ['kode_mk' => "PAI-6-006-{$suffix}", 'nama_mk' => 'Micro Teaching Lanjutan', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Micro Teaching Lanjutan'],
            ['kode_mk' => "PAI-6-007-{$suffix}", 'nama_mk' => 'Bimbingan Penulisan Karya Tulis Ilmiah', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Bimbingan Penulisan Karya Tulis Ilmiah'],
            ['kode_mk' => "PAI-6-008-{$suffix}", 'nama_mk' => 'Teknologi Informasi dan Komunikasi', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Teknologi Informasi dan Komunikasi'],

            // Semester 7 (21 SKS)
            ['kode_mk' => "PAI-7-001-{$suffix}", 'nama_mk' => 'Manajemen Pendidikan Islam', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen Pendidikan Islam'],
            ['kode_mk' => "PAI-7-002-{$suffix}", 'nama_mk' => 'Pendidikan Entrepreneurship', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Pendidikan Entrepreneurship'],
            ['kode_mk' => "PAI-7-003-{$suffix}", 'nama_mk' => 'Pengembangan Profesionalisme Guru', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Pengembangan Profesionalisme Guru'],
            ['kode_mk' => "PAI-7-004-{$suffix}", 'nama_mk' => 'PPL (Praktik Pengalaman Lapangan)', 'sks' => 4, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'PPL (Praktik Pengalaman Lapangan)'],
            ['kode_mk' => "PAI-7-005-{$suffix}", 'nama_mk' => 'Praktek Bahasa Arab', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Praktek Bahasa Arab'],
            ['kode_mk' => "PAI-7-006-{$suffix}", 'nama_mk' => 'Praktek Tahsin dan Tahfidz Alquran Bersanad', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Praktek Tahsin dan Tahfidz Alquran Bersanad'],
            ['kode_mk' => "PAI-7-007-{$suffix}", 'nama_mk' => 'Seminar Pendidikan Islam', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Seminar Pendidikan Islam'],
            ['kode_mk' => "PAI-7-008-{$suffix}", 'nama_mk' => 'Teknologi Pendidikan', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Teknologi Pendidikan'],
            ['kode_mk' => "PAI-7-009-{$suffix}", 'nama_mk' => 'Seminar Proposal', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Seminar Proposal'],

            // Semester 8 (6 SKS)
            ['kode_mk' => "PAI-8-001-{$suffix}", 'nama_mk' => 'Skripsi', 'sks' => 6, 'semester' => 8, 'jenis' => 'wajib', 'deskripsi' => 'Skripsi'],
        ];
    }

    private function getMPIMataKuliah($kodeProdi)
    {
        $suffix = str_contains($kodeProdi, '-D') ? 'D' : 'L';

        return [
            // Semester 1 (18 SKS)
            ['kode_mk' => "MPI-1-001-{$suffix}", 'nama_mk' => 'Pendidikan Pancasila dan Kewarganegaraan', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Pendidikan Pancasila dan Kewarganegaraan'],
            ['kode_mk' => "MPI-1-002-{$suffix}", 'nama_mk' => 'Bahasa Inggris', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Inggris'],
            ['kode_mk' => "MPI-1-003-{$suffix}", 'nama_mk' => 'Aqidah', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Aqidah'],
            ['kode_mk' => "MPI-1-004-{$suffix}", 'nama_mk' => 'Fiqih Ibadah', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Fiqih Ibadah'],
            ['kode_mk' => "MPI-1-005-{$suffix}", 'nama_mk' => 'Bahasa Arab', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab'],
            ['kode_mk' => "MPI-1-006-{$suffix}", 'nama_mk' => 'Sirah Nabawiyah', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Sirah Nabawiyah'],
            ['kode_mk' => "MPI-1-007-{$suffix}", 'nama_mk' => "Tahsin & Tahfidz Alqur'an", 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => "Tahsin & Tahfidz Alqur'an"],
            ['kode_mk' => "MPI-1-008-{$suffix}", 'nama_mk' => 'Ushul Tafshir', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Ushul Tafshir'],
            ['kode_mk' => "MPI-1-009-{$suffix}", 'nama_mk' => 'Adab Akhlak', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Adab Akhlak'],

            // Semester 2 (18 SKS)
            ['kode_mk' => "MPI-2-001-{$suffix}", 'nama_mk' => 'Aqidah', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Aqidah'],
            ['kode_mk' => "MPI-2-002-{$suffix}", 'nama_mk' => "Tahsin & Tahfidz Alqur'an", 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => "Tahsin & Tahfidz Alqur'an"],
            ['kode_mk' => "MPI-2-003-{$suffix}", 'nama_mk' => 'Bahasa Arab', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab'],
            ['kode_mk' => "MPI-2-004-{$suffix}", 'nama_mk' => 'Bahasa Indonesia', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Indonesia'],
            ['kode_mk' => "MPI-2-005-{$suffix}", 'nama_mk' => 'Psikologi Pendidikan Islam', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Psikologi Pendidikan Islam'],
            ['kode_mk' => "MPI-2-006-{$suffix}", 'nama_mk' => 'Fiqih Munakahat dan Warist', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Fiqih Munakahat dan Warist'],
            ['kode_mk' => "MPI-2-007-{$suffix}", 'nama_mk' => "Ulumul Qur'an", 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => "Ulumul Qur'an"],
            ['kode_mk' => "MPI-2-008-{$suffix}", 'nama_mk' => 'Tsaqofah Islamiyah', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Tsaqofah Islamiyah'],
            ['kode_mk' => "MPI-2-009-{$suffix}", 'nama_mk' => 'Bahasa Inggris', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Inggris'],

            // Semester 3 (20 SKS)
            ['kode_mk' => "MPI-3-001-{$suffix}", 'nama_mk' => 'Hadist', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Hadist'],
            ['kode_mk' => "MPI-3-002-{$suffix}", 'nama_mk' => 'Tafsir', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Tafsir'],
            ['kode_mk' => "MPI-3-003-{$suffix}", 'nama_mk' => 'Komunikasi Pendidikan Islam', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Komunikasi Pendidikan Islam'],
            ['kode_mk' => "MPI-3-004-{$suffix}", 'nama_mk' => 'Supervisi Pendidikan Islam', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Supervisi Pendidikan Islam'],
            ['kode_mk' => "MPI-3-005-{$suffix}", 'nama_mk' => 'Bahasa Arab', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab'],
            ['kode_mk' => "MPI-3-006-{$suffix}", 'nama_mk' => 'Literasi Digital Islam', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Literasi Digital Islam'],
            ['kode_mk' => "MPI-3-007-{$suffix}", 'nama_mk' => 'Metodologi Studi Islam', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi Studi Islam'],
            ['kode_mk' => "MPI-3-008-{$suffix}", 'nama_mk' => 'Sejarah Pendidikan Islam', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Sejarah Pendidikan Islam'],
            ['kode_mk' => "MPI-3-009-{$suffix}", 'nama_mk' => 'Fiqih Dakwah', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Fiqih Dakwah'],
            ['kode_mk' => "MPI-3-010-{$suffix}", 'nama_mk' => 'Tahsin & Tahfidzh Alquran', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Tahsin & Tahfidzh Alquran'],

            // Semester 4 (22 SKS)
            ['kode_mk' => "MPI-4-001-{$suffix}", 'nama_mk' => 'Media Pengajaran Berbasis Aplikasi', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Media Pengajaran Berbasis Aplikasi'],
            ['kode_mk' => "MPI-4-002-{$suffix}", 'nama_mk' => 'Tafsir Tarbawi', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Tafsir Tarbawi'],
            ['kode_mk' => "MPI-4-003-{$suffix}", 'nama_mk' => 'Metode Penelitian Kuantitatif', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Metode Penelitian Kuantitatif'],
            ['kode_mk' => "MPI-4-004-{$suffix}", 'nama_mk' => 'Aqidah', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Aqidah'],
            ['kode_mk' => "MPI-4-005-{$suffix}", 'nama_mk' => 'Bahasa Arab', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab'],
            ['kode_mk' => "MPI-4-006-{$suffix}", 'nama_mk' => 'Tahsin dan Tahfidz Alquran', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Tahsin dan Tahfidz Alquran'],
            ['kode_mk' => "MPI-4-007-{$suffix}", 'nama_mk' => 'Manajemen Pengelolaan SDM', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen Pengelolaan SDM'],
            ['kode_mk' => "MPI-4-008-{$suffix}", 'nama_mk' => 'Fiqih Muyassar', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Fiqih Muyassar'],
            ['kode_mk' => "MPI-4-009-{$suffix}", 'nama_mk' => 'Hadist', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Hadist'],
            ['kode_mk' => "MPI-4-010-{$suffix}", 'nama_mk' => 'Filsafat Pendidikan Islam dalam Timbangan Syariat', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Filsafat Pendidikan Islam dalam Timbangan Syariat'],
            ['kode_mk' => "MPI-4-011-{$suffix}", 'nama_mk' => 'Perencanaan Pembelajaran Berbasis IT', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Perencanaan Pembelajaran Berbasis IT'],

            // Semester 5 (18 SKS)
            ['kode_mk' => "MPI-5-001-{$suffix}", 'nama_mk' => 'Micro Teaching', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Micro Teaching'],
            ['kode_mk' => "MPI-5-002-{$suffix}", 'nama_mk' => 'Metode Penelitian Kualitatif', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Metode Penelitian Kualitatif'],
            ['kode_mk' => "MPI-5-003-{$suffix}", 'nama_mk' => 'Administrasi Pendidikan Islam berbasis IT', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Administrasi Pendidikan Islam berbasis IT'],
            ['kode_mk' => "MPI-5-004-{$suffix}", 'nama_mk' => 'Bahasa Arab', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab'],
            ['kode_mk' => "MPI-5-005-{$suffix}", 'nama_mk' => 'Tahsin dan Tahfidz Alquran', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Tahsin dan Tahfidz Alquran'],
            ['kode_mk' => "MPI-5-006-{$suffix}", 'nama_mk' => 'Fiqih', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Fiqih'],
            ['kode_mk' => "MPI-5-007-{$suffix}", 'nama_mk' => 'Pengembangan Kurikulum', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Pengembangan Kurikulum'],
            ['kode_mk' => "MPI-5-008-{$suffix}", 'nama_mk' => 'Statistik Pendidikan', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Statistik Pendidikan'],

            // Semester 6 (18 SKS)
            ['kode_mk' => "MPI-6-001-{$suffix}", 'nama_mk' => 'Manajemen berbasis Sekolah', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen berbasis Sekolah'],
            ['kode_mk' => "MPI-6-002-{$suffix}", 'nama_mk' => 'Bahasa Arab', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab'],
            ['kode_mk' => "MPI-6-003-{$suffix}", 'nama_mk' => 'Tahsin dan Tahfidz Alquran', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Tahsin dan Tahfidz Alquran'],
            ['kode_mk' => "MPI-6-004-{$suffix}", 'nama_mk' => 'Aqidah', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Aqidah'],
            ['kode_mk' => "MPI-6-005-{$suffix}", 'nama_mk' => 'KKN', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'KKN'],
            ['kode_mk' => "MPI-6-006-{$suffix}", 'nama_mk' => 'Micro Teaching Lanjutan', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Micro Teaching Lanjutan'],
            ['kode_mk' => "MPI-6-007-{$suffix}", 'nama_mk' => 'Bimbingan Penulisan Karya Tulis Ilmiah', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Bimbingan Penulisan Karya Tulis Ilmiah'],
            ['kode_mk' => "MPI-6-008-{$suffix}", 'nama_mk' => 'Teknologi Informasi dan Komunikasi', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Teknologi Informasi dan Komunikasi'],

            // Semester 7 (21 SKS)
            ['kode_mk' => "MPI-7-001-{$suffix}", 'nama_mk' => 'Manajemen Pendidikan Islam', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen Pendidikan Islam'],
            ['kode_mk' => "MPI-7-002-{$suffix}", 'nama_mk' => 'Pendidikan Entrepreneurship', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Pendidikan Entrepreneurship'],
            ['kode_mk' => "MPI-7-003-{$suffix}", 'nama_mk' => 'Pengembangan Profesionalisme Guru', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Pengembangan Profesionalisme Guru'],
            ['kode_mk' => "MPI-7-004-{$suffix}", 'nama_mk' => 'PPL', 'sks' => 4, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'PPL'],
            ['kode_mk' => "MPI-7-005-{$suffix}", 'nama_mk' => 'Praktek Bahasa Arab', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Praktek Bahasa Arab'],
            ['kode_mk' => "MPI-7-006-{$suffix}", 'nama_mk' => 'Praktek Tahsin dan Tahfidz Alquran Bersanad', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Praktek Tahsin dan Tahfidz Alquran Bersanad'],
            ['kode_mk' => "MPI-7-007-{$suffix}", 'nama_mk' => 'Seminar Pendidikan Islam', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Seminar Pendidikan Islam'],
            ['kode_mk' => "MPI-7-008-{$suffix}", 'nama_mk' => 'Teknologi Pendidikan', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Teknologi Pendidikan'],
            ['kode_mk' => "MPI-7-009-{$suffix}", 'nama_mk' => 'Seminar Proposal', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Seminar Proposal'],

            // Semester 8 (6 SKS)
            ['kode_mk' => "MPI-8-001-{$suffix}", 'nama_mk' => 'Skripsi', 'sks' => 6, 'semester' => 8, 'jenis' => 'wajib', 'deskripsi' => 'Skripsi'],
        ];
    }

    private function getHESMataKuliah($kodeProdi)
    {
        // Add suffix to make course codes unique between Daring and Luring
        $suffix = str_contains($kodeProdi, '-D') ? 'D' : 'L';

        return [
            // Semester 1
            ['kode_mk' => "MKB-02-001-{$suffix}", 'nama_mk' => 'Pengantar Bisnis Syariah', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Mata kuliah pengantar yang membahas konsep dasar bisnis dalam perspektif syariah'],
            ['kode_mk' => "MKK-02-003-{$suffix}", 'nama_mk' => 'Ekonomi Mikro Syariah', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari teori ekonomi mikro dalam perspektif syariah'],
            ['kode_mk' => "MPB-02-001-{$suffix}", 'nama_mk' => 'Fiqih Ibadah', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari hukum Islam terkait ibadah'],
            ['kode_mk' => "MPK-02-001-{$suffix}", 'nama_mk' => 'Bahasa Arab', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Pembelajaran bahasa Arab untuk keperluan akademik'],
            ['kode_mk' => "MKK-02-014-{$suffix}", 'nama_mk' => 'Pengantar Ilmu Hukum', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Pengantar dasar-dasar ilmu hukum'],
            ['kode_mk' => "MKK-02-001-{$suffix}", 'nama_mk' => 'Fikih Muamalah', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari hukum Islam terkait transaksi dan hubungan antar manusia'],
            ['kode_mk' => "MBB-02-001-{$suffix}", 'nama_mk' => 'Pancasila dan Kewarganegaraan', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Mata kuliah wajib nasional tentang Pancasila dan kewarganegaraan'],

            // Semester 2
            ['kode_mk' => "MKK-02-003A-{$suffix}", 'nama_mk' => 'Sejarah Hukum', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari sejarah perkembangan hukum'],
            ['kode_mk' => "MKK-02-004-{$suffix}", 'nama_mk' => 'Ekonomi Makro Syariah', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari teori ekonomi makro dalam perspektif syariah'],
            ['kode_mk' => "MKK-02-004A-{$suffix}", 'nama_mk' => 'Teori Akad Ekonomi Syariah', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari teori kontrak/akad dalam ekonomi syariah'],
            ['kode_mk' => "MPB-02-002-{$suffix}", 'nama_mk' => 'Ushul Fiqih Ekonomi', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari dasar-dasar metodologi hukum Islam dalam ekonomi'],
            ['kode_mk' => "MKK-02-015-{$suffix}", 'nama_mk' => 'Hukum Perdata', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari hukum perdata Indonesia'],
            ['kode_mk' => "MPK-02-003-{$suffix}", 'nama_mk' => 'Bahasa Inggris', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Pembelajaran bahasa Inggris untuk keperluan akademik'],
            ['kode_mk' => "MKK-02-016-{$suffix}", 'nama_mk' => 'Filsafat Hukum Islam', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari filosofi dan dasar pemikiran hukum Islam'],

            // Semester 3
            ['kode_mk' => "MPB-02-003-{$suffix}", 'nama_mk' => 'Hukum Ziswaf', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari hukum zakat, infak, sedekah, dan wakaf'],
            ['kode_mk' => "MKK-02-005-{$suffix}", 'nama_mk' => 'Sejarah Pemikiran Ekonomi Islam', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari sejarah perkembangan pemikiran ekonomi Islam'],
            ['kode_mk' => "MKK-02-009-{$suffix}", 'nama_mk' => 'Hukum Pidana', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari hukum pidana Indonesia'],
            ['kode_mk' => "MKB-02-005-{$suffix}", 'nama_mk' => 'Statistik', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari metode statistik untuk analisis data'],
            ['kode_mk' => "MKK-02-017-{$suffix}", 'nama_mk' => 'Hukum Perjanjian', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari hukum perjanjian/kontrak'],
            ['kode_mk' => "MKK-02-018-{$suffix}", 'nama_mk' => 'Hukum Perusahaan', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari hukum yang mengatur perusahaan'],
            ['kode_mk' => "MKK-02-019-{$suffix}", 'nama_mk' => 'Hukum Tata Negara', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari hukum tata negara Indonesia'],
            ['kode_mk' => "MKK-02-002-{$suffix}", 'nama_mk' => 'Bank dan Lembaga Keuangan Syariah', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari bank dan lembaga keuangan syariah'],
            ['kode_mk' => "MPB-02-008-{$suffix}", 'nama_mk' => 'Ayat dan Hadist Ekonomi', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari ayat Al-Quran dan hadist yang terkait ekonomi'],

            // Semester 4
            ['kode_mk' => "MKK-02-020-{$suffix}", 'nama_mk' => 'Hukum Ketenagakerjaan', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari hukum ketenagakerjaan Indonesia'],
            ['kode_mk' => "MKK-02-021-{$suffix}", 'nama_mk' => 'Hukum Perlindungan Konsumen', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari hukum perlindungan konsumen'],
            ['kode_mk' => "MKK-02-022-{$suffix}", 'nama_mk' => 'Bahasa Indonesia', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Mata kuliah wajib nasional bahasa Indonesia'],
            ['kode_mk' => "MKB-02-029-{$suffix}", 'nama_mk' => 'Pasar Modal Syariah', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari pasar modal berbasis syariah'],
            ['kode_mk' => "MPB-02-005-{$suffix}", 'nama_mk' => 'Hukum Waris', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari hukum waris dalam Islam'],
            ['kode_mk' => "MKB-02-006-{$suffix}", 'nama_mk' => 'Islamic Entrepreneurship', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari kewirausahaan dalam perspektif Islam'],
            ['kode_mk' => "MPB-02-007-{$suffix}", 'nama_mk' => 'Aqidah', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari akidah/keyakinan dalam Islam'],

            // Semester 5
            ['kode_mk' => "MBB-02-022-{$suffix}", 'nama_mk' => 'Fiqih Dakwah', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari hukum Islam terkait dakwah'],
            ['kode_mk' => "MKK-02-007-{$suffix}", 'nama_mk' => 'Ilmu Perundang-Undangan', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari ilmu perundang-undangan Indonesia'],
            ['kode_mk' => "MKK-02-008-{$suffix}", 'nama_mk' => 'Hukum Acara Perdata', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari hukum acara perdata'],
            ['kode_mk' => "MKK-02-023-{$suffix}", 'nama_mk' => 'Kontrak Drafting', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari teknik penyusunan kontrak'],
            ['kode_mk' => "MKK-02-024-{$suffix}", 'nama_mk' => 'Hukum Agraria', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari hukum agraria/pertanahan'],
            ['kode_mk' => "MPB-02-010-{$suffix}", 'nama_mk' => 'Sirah Nabawiyah', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari sejarah kehidupan Nabi Muhammad SAW'],

            // Semester 6
            ['kode_mk' => "MKB-02-010-{$suffix}", 'nama_mk' => 'Metodologi Penelitian Hukum Bisnis', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari metodologi penelitian di bidang hukum bisnis'],
            ['kode_mk' => "MKK-02-013-{$suffix}", 'nama_mk' => 'Hukum Acara Pidana', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari hukum acara pidana'],
            ['kode_mk' => "MKK-02-010-{$suffix}", 'nama_mk' => 'Peradilan Agama', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari sistem peradilan agama di Indonesia'],
            ['kode_mk' => "MKK-02-012-{$suffix}", 'nama_mk' => 'Regulasi dan Supervisi Lembaga Keuangan Syariah', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari regulasi dan pengawasan lembaga keuangan syariah'],
            ['kode_mk' => "MKK-02-025-{$suffix}", 'nama_mk' => 'Penyelesaian Perselisihan Hubungan Industrial', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari penyelesaian perselisihan dalam hubungan industrial'],

            // Semester 7
            ['kode_mk' => "MBB-02-003-{$suffix}", 'nama_mk' => 'PKL (Praktik Kerja Lapangan)', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Praktik kerja lapangan untuk pengalaman industri'],
            ['kode_mk' => "MKB-02-011-{$suffix}", 'nama_mk' => 'Penyelesaian Sengketa Bisnis Syariah', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari cara penyelesaian sengketa bisnis syariah'],
            ['kode_mk' => "MKK-02-026-{$suffix}", 'nama_mk' => 'Keterampilan Kompetensi Hukum', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari keterampilan dan kompetensi di bidang hukum'],
            ['kode_mk' => "MKK-02-014A-{$suffix}", 'nama_mk' => 'Seminar Ekonomi Syariah', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Seminar terkait isu-isu terkini ekonomi syariah'],
            ['kode_mk' => "MKK-02-027-{$suffix}", 'nama_mk' => 'Hukum Acara Peradilan Tata Usaha Negara', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari hukum acara peradilan tata usaha negara'],
            ['kode_mk' => "MKK-02-028-{$suffix}", 'nama_mk' => 'Politik Hukum', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Mempelajari politik hukum Indonesia'],

            // Semester 8
            ['kode_mk' => "MKB-02-014-{$suffix}", 'nama_mk' => 'Tugas Akhir', 'sks' => 6, 'semester' => 8, 'jenis' => 'wajib', 'deskripsi' => 'Penulisan skripsi sebagai tugas akhir program studi'],
        ];
    }

    private function getPGMIMataKuliah($kodeProdi)
    {
        $suffix = str_contains($kodeProdi, '-D') ? 'D' : 'L';

        return [
            // Semester 1 (19 SKS)
            ['kode_mk' => "PGMI-1-001-{$suffix}", 'nama_mk' => 'Metodelogi Studi Islam', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Metodelogi Studi Islam'],
            ['kode_mk' => "PGMI-1-002-{$suffix}", 'nama_mk' => 'Bahasa Inggris 1', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Inggris 1'],
            ['kode_mk' => "PGMI-1-003-{$suffix}", 'nama_mk' => 'Bahasa Arab 1', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab 1'],
            ['kode_mk' => "PGMI-1-004-{$suffix}", 'nama_mk' => 'Pancasila dan Pendidikan Kewarganegaraan', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Pancasila dan Pendidikan Kewarganegaraan'],
            ['kode_mk' => "PGMI-1-005-{$suffix}", 'nama_mk' => 'Aqidah', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Aqidah'],
            ['kode_mk' => "PGMI-1-006-{$suffix}", 'nama_mk' => 'Fiqih Ibadah', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Fiqih Ibadah'],
            ['kode_mk' => "PGMI-1-007-{$suffix}", 'nama_mk' => 'Dasar-dasar Pendidikan Islam', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Dasar-dasar Pendidikan Islam'],
            ['kode_mk' => "PGMI-1-008-{$suffix}", 'nama_mk' => 'Tahsin dan Tahfidz', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Tahsin dan Tahfidz'],

            // Semester 2 (21 SKS)
            ['kode_mk' => "PGMI-2-001-{$suffix}", 'nama_mk' => 'Bahasa Indonesia', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Indonesia'],
            ['kode_mk' => "PGMI-2-002-{$suffix}", 'nama_mk' => 'Bahasa Arab II', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab II'],
            ['kode_mk' => "PGMI-2-003-{$suffix}", 'nama_mk' => 'Kajian PAI MI/SD', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Kajian PAI MI/SD'],
            ['kode_mk' => "PGMI-2-004-{$suffix}", 'nama_mk' => 'Tahsin dan Tahfidz', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Tahsin dan Tahfidz'],
            ['kode_mk' => "PGMI-2-005-{$suffix}", 'nama_mk' => 'Ilmu Pendidikan Islam', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Ilmu Pendidikan Islam'],
            ['kode_mk' => "PGMI-2-006-{$suffix}", 'nama_mk' => 'Bahasa Inggris II', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Inggris II'],
            ['kode_mk' => "PGMI-2-007-{$suffix}", 'nama_mk' => 'Aqidah', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Aqidah'],
            ['kode_mk' => "PGMI-2-008-{$suffix}", 'nama_mk' => 'Fiqih', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Fiqih'],
            ['kode_mk' => "PGMI-2-009-{$suffix}", 'nama_mk' => 'Fiqih Muamalah', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Fiqih Muamalah'],
            ['kode_mk' => "PGMI-2-010-{$suffix}", 'nama_mk' => 'Media Pengajaran', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Media Pengajaran'],

            // Semester 3 (21 SKS)
            ['kode_mk' => "PGMI-3-001-{$suffix}", 'nama_mk' => 'Bahasa Arab III', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab III'],
            ['kode_mk' => "PGMI-3-002-{$suffix}", 'nama_mk' => 'Tahsin dan Tahfidz', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Tahsin dan Tahfidz'],
            ['kode_mk' => "PGMI-3-003-{$suffix}", 'nama_mk' => 'Fiqih Muamalah', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Fiqih Muamalah'],
            ['kode_mk' => "PGMI-3-004-{$suffix}", 'nama_mk' => 'Aqidah', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Aqidah'],
            ['kode_mk' => "PGMI-3-005-{$suffix}", 'nama_mk' => 'Sejarah Peradaban Islam', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Sejarah Peradaban Islam'],
            ['kode_mk' => "PGMI-3-006-{$suffix}", 'nama_mk' => 'Psikologi PAI MI/SD', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Psikologi PAI MI/SD'],
            ['kode_mk' => "PGMI-3-007-{$suffix}", 'nama_mk' => 'BK Anak Usia MI/SD', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'BK Anak Usia MI/SD'],
            ['kode_mk' => "PGMI-3-008-{$suffix}", 'nama_mk' => 'Teori Belajar dan Pembelajaran Usia MI/SD', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Teori Belajar dan Pembelajaran Usia MI/SD'],
            ['kode_mk' => "PGMI-3-009-{$suffix}", 'nama_mk' => 'Tafsir Pendidikan', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Tafsir Pendidikan'],
            ['kode_mk' => "PGMI-3-010-{$suffix}", 'nama_mk' => 'Media Pembelajaran Anak Usia MI/SD', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Media Pembelajaran Anak Usia MI/SD'],

            // Semester 4 (17 SKS)
            ['kode_mk' => "PGMI-4-001-{$suffix}", 'nama_mk' => 'Bahasa Arab IV', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab IV'],
            ['kode_mk' => "PGMI-4-002-{$suffix}", 'nama_mk' => 'Tahsin dan Tahfidz', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Tahsin dan Tahfidz'],
            ['kode_mk' => "PGMI-4-003-{$suffix}", 'nama_mk' => 'Psikologi Pendidikan Agama Islam', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Psikologi Pendidikan Agama Islam'],
            ['kode_mk' => "PGMI-4-004-{$suffix}", 'nama_mk' => 'Pembelajaran Bahasa Indonesia MI/SD', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Pembelajaran Bahasa Indonesia MI/SD'],
            ['kode_mk' => "PGMI-4-005-{$suffix}", 'nama_mk' => 'Fiqih Munakahat', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Fiqih Munakahat'],
            ['kode_mk' => "PGMI-4-006-{$suffix}", 'nama_mk' => 'Pembelajaran MI', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Pembelajaran MI'],
            ['kode_mk' => "PGMI-4-007-{$suffix}", 'nama_mk' => 'Metodologi Penelitian Kuantitatif', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi Penelitian Kuantitatif'],

            // Semester 5 (14 SKS)
            ['kode_mk' => "PGMI-5-001-{$suffix}", 'nama_mk' => 'Bahasa Arab V', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab V'],
            ['kode_mk' => "PGMI-5-002-{$suffix}", 'nama_mk' => 'Tahsin dan Tahfidz', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Tahsin dan Tahfidz'],
            ['kode_mk' => "PGMI-5-003-{$suffix}", 'nama_mk' => 'Statistik Pendidikan', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Statistik Pendidikan'],
            ['kode_mk' => "PGMI-5-004-{$suffix}", 'nama_mk' => 'Metodologi Penelitian Kualitatif', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi Penelitian Kualitatif'],
            ['kode_mk' => "PGMI-5-005-{$suffix}", 'nama_mk' => 'Perkembangan Peserta Didik MI/SD', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Perkembangan Peserta Didik MI/SD'],
            ['kode_mk' => "PGMI-5-006-{$suffix}", 'nama_mk' => 'Bimbingan Konseling', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Bimbingan Konseling'],

            // Semester 6 (26 SKS)
            ['kode_mk' => "PGMI-6-001-{$suffix}", 'nama_mk' => 'Bahasa Arab VI', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab VI'],
            ['kode_mk' => "PGMI-6-002-{$suffix}", 'nama_mk' => 'Tahsin dan Tahfidz', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Tahsin dan Tahfidz'],
            ['kode_mk' => "PGMI-6-003-{$suffix}", 'nama_mk' => 'Sirah Nabawiyah', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Sirah Nabawiyah'],
            ['kode_mk' => "PGMI-6-004-{$suffix}", 'nama_mk' => 'Micro Teaching', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Micro Teaching'],
            ['kode_mk' => "PGMI-6-005-{$suffix}", 'nama_mk' => 'Metodologi Penelitian Kuantitatif', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi Penelitian Kuantitatif'],
            ['kode_mk' => "PGMI-6-006-{$suffix}", 'nama_mk' => 'Pendidikan Berkebutuhan Khusus', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Pendidikan Berkebutuhan Khusus'],
            ['kode_mk' => "PGMI-6-007-{$suffix}", 'nama_mk' => 'Penulisan Karya Tulis Ilmiah', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Penulisan Karya Tulis Ilmiah'],
            ['kode_mk' => "PGMI-6-008-{$suffix}", 'nama_mk' => 'Evaluasi Pembelajaran MI/SD', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Evaluasi Pembelajaran MI/SD'],
            ['kode_mk' => "PGMI-6-009-{$suffix}", 'nama_mk' => 'Pembelajaran Quran Hadist MI/SD', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Pembelajaran Quran Hadist MI/SD'],
            ['kode_mk' => "PGMI-6-010-{$suffix}", 'nama_mk' => 'Pembelajaran Aqidah Akhlak MI/SD', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Pembelajaran Aqidah Akhlak MI/SD'],
            ['kode_mk' => "PGMI-6-011-{$suffix}", 'nama_mk' => 'Pembelajaran Fiqih MI/SD', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Pembelajaran Fiqih MI/SD'],

            // Semester 7 (31 SKS)
            ['kode_mk' => "PGMI-7-001-{$suffix}", 'nama_mk' => 'Pembelajaran Bahasa Arab MI/SD', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Pembelajaran Bahasa Arab MI/SD'],
            ['kode_mk' => "PGMI-7-002-{$suffix}", 'nama_mk' => 'Pembelajaran SKI MI/SD', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Pembelajaran SKI MI/SD'],
            ['kode_mk' => "PGMI-7-003-{$suffix}", 'nama_mk' => 'Pembelajaran Matematika MI/SD', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Pembelajaran Matematika MI/SD'],
            ['kode_mk' => "PGMI-7-004-{$suffix}", 'nama_mk' => 'Pembelajaran IPA MI/SD', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Pembelajaran IPA MI/SD'],
            ['kode_mk' => "PGMI-7-005-{$suffix}", 'nama_mk' => 'Pembelajaran IPS MI/SD', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Pembelajaran IPS MI/SD'],
            ['kode_mk' => "PGMI-7-006-{$suffix}", 'nama_mk' => 'Pembelajaran SBK MI/SD', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Pembelajaran SBK MI/SD'],
            ['kode_mk' => "PGMI-7-007-{$suffix}", 'nama_mk' => 'Pembelajaran PJOK MI/SD', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Pembelajaran PJOK MI/SD'],
            ['kode_mk' => "PGMI-7-008-{$suffix}", 'nama_mk' => 'PPL', 'sks' => 4, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'PPL'],
            ['kode_mk' => "PGMI-7-009-{$suffix}", 'nama_mk' => 'KKN', 'sks' => 4, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'KKN'],
            ['kode_mk' => "PGMI-7-010-{$suffix}", 'nama_mk' => 'Praktek Bahasa Arab', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Praktek Bahasa Arab'],
            ['kode_mk' => "PGMI-7-011-{$suffix}", 'nama_mk' => 'Tahsin dan Tahfidz', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Tahsin dan Tahfidz'],

            // Semester 8 (6 SKS)
            ['kode_mk' => "PGMI-8-001-{$suffix}", 'nama_mk' => 'Skripsi', 'sks' => 6, 'semester' => 8, 'jenis' => 'wajib', 'deskripsi' => 'Skripsi'],
        ];
    }
}
