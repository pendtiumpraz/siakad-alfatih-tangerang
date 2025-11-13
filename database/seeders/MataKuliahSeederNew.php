<?php

namespace Database\Seeders;

use App\Models\Kurikulum;
use App\Models\MataKuliah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MataKuliahSeederNew extends Seeder
{
    /**
     * STAI AL-FATIH Tangerang - Mata Kuliah Seeder
     * Simplified version for 4 main programs: PAI, ES, PIAUD, MPI
     */
    public function run(): void
    {
        $kurikulums = Kurikulum::with('programStudi')->get();

        foreach ($kurikulums as $kurikulum) {
            $prodiKode = $kurikulum->programStudi->kode_prodi;

            switch ($prodiKode) {
                case 'PAI':
                    $mataKuliahs = $this->getPAIMataKuliah();
                    break;
                case 'ES':
                    $mataKuliahs = $this->getESMataKuliah();
                    break;
                case 'PIAUD':
                    $mataKuliahs = $this->getPIAUDMataKuliah();
                    break;
                case 'MPI':
                    $mataKuliahs = $this->getMPIMataKuliah();
                    break;
                default:
                    continue 2; // Skip unknown programs
            }

            foreach ($mataKuliahs as $mk) {
                MataKuliah::firstOrCreate(
                    [
                        'kode_mk' => $mk['kode_mk'],
                        'kurikulum_id' => $kurikulum->id,
                    ],
                    $mk
                );
            }

            $courseCount = count($mataKuliahs);
            $this->command->info("✓ {$prodiKode}: {$courseCount} mata kuliah");
        }

        $this->command->info('✓ Mata Kuliah seeding completed!');
    }

    private function getPAIMataKuliah()
    {
        return [
            // Semester 1 (18 SKS)
            ['kode_mk' => 'PAI-101', 'nama_mk' => 'Pendidikan Pancasila dan Kewarganegaraan', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Mata kuliah wajib nasional tentang Pancasila'],
            ['kode_mk' => 'PAI-102', 'nama_mk' => 'Bahasa Inggris', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Inggris untuk akademik'],
            ['kode_mk' => 'PAI-103', 'nama_mk' => 'Aqidah', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Kajian akidah Islam'],
            ['kode_mk' => 'PAI-104', 'nama_mk' => 'Fiqih Ibadah', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Hukum Islam tentang ibadah'],
            ['kode_mk' => 'PAI-105', 'nama_mk' => 'Bahasa Arab I', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab dasar'],
            ['kode_mk' => 'PAI-106', 'nama_mk' => 'Sirah Nabawiyah', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Sejarah kehidupan Nabi Muhammad SAW'],
            ['kode_mk' => 'PAI-107', 'nama_mk' => "Tahsin & Tahfidz Al-Qur'an I", 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Perbaikan bacaan dan hafalan Al-Quran'],
            ['kode_mk' => 'PAI-108', 'nama_mk' => 'Ushul Tafsir', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Dasar-dasar ilmu tafsir'],
            ['kode_mk' => 'PAI-109', 'nama_mk' => 'Akhlak Tasawuf', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Kajian akhlak dan tasawuf'],

            // Semester 2 (18 SKS)
            ['kode_mk' => 'PAI-201', 'nama_mk' => 'Bahasa Indonesia', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Mata kuliah wajib nasional bahasa Indonesia'],
            ['kode_mk' => 'PAI-202', 'nama_mk' => 'Bahasa Arab II', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab lanjutan'],
            ['kode_mk' => 'PAI-203', 'nama_mk' => "Tahsin & Tahfidz Al-Qur'an II", 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Tahsin dan hafalan lanjutan'],
            ['kode_mk' => 'PAI-204', 'nama_mk' => 'Psikologi Pendidikan Islam', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Psikologi dalam pendidikan Islam'],
            ['kode_mk' => 'PAI-205', 'nama_mk' => 'Fiqih Munakahat dan Mawaris', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Hukum pernikahan dan waris'],
            ['kode_mk' => 'PAI-206', 'nama_mk' => "Ulumul Qur'an", 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => "Ilmu-ilmu Al-Qur'an"],
            ['kode_mk' => 'PAI-207', 'nama_mk' => 'Sejarah Peradaban Islam', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Sejarah peradaban Islam'],
            ['kode_mk' => 'PAI-208', 'nama_mk' => 'Ilmu Hadits', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Ilmu hadits dan metodologinya'],

            // Semester 3 (20 SKS)
            ['kode_mk' => 'PAI-301', 'nama_mk' => 'Metodologi Studi Islam', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi dalam studi Islam'],
            ['kode_mk' => 'PAI-302', 'nama_mk' => 'Tafsir Tarbawi', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Tafsir ayat-ayat pendidikan'],
            ['kode_mk' => 'PAI-303', 'nama_mk' => 'Ilmu Pendidikan Islam', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Konsep dan teori pendidikan Islam'],
            ['kode_mk' => 'PAI-304', 'nama_mk' => 'Bahasa Arab III', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab tingkat menengah'],
            ['kode_mk' => 'PAI-305', 'nama_mk' => "Tahsin & Tahfidz Al-Qur'an III", 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Tahsin dan hafalan tingkat lanjut'],
            ['kode_mk' => 'PAI-306', 'nama_mk' => 'Strategi Pembelajaran PAI', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Strategi mengajar PAI'],
            ['kode_mk' => 'PAI-307', 'nama_mk' => 'Media Pembelajaran', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Media dan teknologi pembelajaran'],
            ['kode_mk' => 'PAI-308', 'nama_mk' => 'Sejarah Pendidikan Islam', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Sejarah perkembangan pendidikan Islam'],
            ['kode_mk' => 'PAI-309', 'nama_mk' => 'Fiqih Siyasah', 'sks' => 1, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Hukum Islam tentang politik dan pemerintahan'],

            // Semester 4 (20 SKS)
            ['kode_mk' => 'PAI-401', 'nama_mk' => 'Filsafat Pendidikan Islam', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Filsafat pendidikan dalam Islam'],
            ['kode_mk' => 'PAI-402', 'nama_mk' => 'Metode Penelitian Kuantitatif', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi penelitian kuantitatif'],
            ['kode_mk' => 'PAI-403', 'nama_mk' => 'Evaluasi Pembelajaran', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Evaluasi dan penilaian pembelajaran'],
            ['kode_mk' => 'PAI-404', 'nama_mk' => 'Perencanaan Pembelajaran', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Perencanaan pembelajaran berbasis IT'],
            ['kode_mk' => 'PAI-405', 'nama_mk' => 'Bahasa Arab IV', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab tingkat tinggi'],
            ['kode_mk' => 'PAI-406', 'nama_mk' => "Tahsin & Tahfidz Al-Qur'an IV", 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Tahsin dan hafalan mahir'],
            ['kode_mk' => 'PAI-407', 'nama_mk' => 'Manajemen Pendidikan Islam', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen lembaga pendidikan Islam'],
            ['kode_mk' => 'PAI-408', 'nama_mk' => 'Hadits Tarbawi', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Hadits-hadits tentang pendidikan'],

            // Semester 5 (18 SKS)
            ['kode_mk' => 'PAI-501', 'nama_mk' => 'Metode Penelitian Kualitatif', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi penelitian kualitatif'],
            ['kode_mk' => 'PAI-502', 'nama_mk' => 'Micro Teaching', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Praktik mengajar mikro'],
            ['kode_mk' => 'PAI-503', 'nama_mk' => 'Pengembangan Kurikulum PAI', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Pengembangan kurikulum PAI'],
            ['kode_mk' => 'PAI-504', 'nama_mk' => 'Statistik Pendidikan', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Statistik untuk penelitian pendidikan'],
            ['kode_mk' => 'PAI-505', 'nama_mk' => 'Bahasa Arab V', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Praktik bahasa Arab'],
            ['kode_mk' => 'PAI-506', 'nama_mk' => "Tahfidz Al-Qur'an V", 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Praktik hafalan Al-Quran'],
            ['kode_mk' => 'PAI-507', 'nama_mk' => 'Bimbingan Konseling Islami', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Bimbingan konseling perspektif Islam'],

            // Semester 6 (17 SKS)
            ['kode_mk' => 'PAI-601', 'nama_mk' => 'Manajemen Berbasis Sekolah', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen berbasis sekolah'],
            ['kode_mk' => 'PAI-602', 'nama_mk' => 'Teknologi Informasi dan Komunikasi', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'TIK dalam pembelajaran'],
            ['kode_mk' => 'PAI-603', 'nama_mk' => 'Seminar Pendidikan Islam', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Seminar isu pendidikan Islam'],
            ['kode_mk' => 'PAI-604', 'nama_mk' => 'Penulisan Karya Ilmiah', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Teknik penulisan karya ilmiah'],
            ['kode_mk' => 'PAI-605', 'nama_mk' => 'Micro Teaching Lanjutan', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Praktik mengajar lanjutan'],
            ['kode_mk' => 'PAI-606', 'nama_mk' => 'Kuliah Kerja Nyata (KKN)', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'KKN di masyarakat'],
            ['kode_mk' => 'PAI-607', 'nama_mk' => "Praktik Tahfidz Al-Qur'an Bersanad", 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Praktik hafalan bersanad'],

            // Semester 7 (19 SKS)
            ['kode_mk' => 'PAI-701', 'nama_mk' => 'Praktik Pengalaman Lapangan (PPL)', 'sks' => 4, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'PPL di sekolah/madrasah'],
            ['kode_mk' => 'PAI-702', 'nama_mk' => 'Pengembangan Profesionalisme Guru', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Profesionalisme guru PAI'],
            ['kode_mk' => 'PAI-703', 'nama_mk' => 'Pendidikan Entrepreneurship', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Kewirausahaan dalam pendidikan'],
            ['kode_mk' => 'PAI-704', 'nama_mk' => 'Teknologi Pendidikan', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Teknologi dalam pendidikan'],
            ['kode_mk' => 'PAI-705', 'nama_mk' => 'Seminar Proposal Skripsi', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Seminar proposal penelitian'],
            ['kode_mk' => 'PAI-706', 'nama_mk' => 'Pembelajaran Berbasis Digital', 'sks' => 3, 'semester' => 7, 'jenis' => 'pilihan', 'deskripsi' => 'Pembelajaran berbasis teknologi digital'],
            ['kode_mk' => 'PAI-707', 'nama_mk' => 'Pendidikan Multikultural', 'sks' => 2, 'semester' => 7, 'jenis' => 'pilihan', 'deskripsi' => 'Pendidikan dalam konteks multikultural'],

            // Semester 8 (6 SKS)
            ['kode_mk' => 'PAI-801', 'nama_mk' => 'Skripsi', 'sks' => 6, 'semester' => 8, 'jenis' => 'wajib', 'deskripsi' => 'Penelitian dan penulisan skripsi'],
        ];
    }

    private function getESMataKuliah()
    {
        return [
            // Semester 1 (18 SKS)
            ['kode_mk' => 'ES-101', 'nama_mk' => 'Pendidikan Pancasila dan Kewarganegaraan', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Mata kuliah wajib nasional tentang Pancasila'],
            ['kode_mk' => 'ES-102', 'nama_mk' => 'Bahasa Inggris', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Inggris untuk akademik'],
            ['kode_mk' => 'ES-103', 'nama_mk' => 'Aqidah', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Kajian akidah Islam'],
            ['kode_mk' => 'ES-104', 'nama_mk' => 'Fiqih Ibadah', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Hukum Islam tentang ibadah'],
            ['kode_mk' => 'ES-105', 'nama_mk' => 'Bahasa Arab I', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab dasar'],
            ['kode_mk' => 'ES-106', 'nama_mk' => 'Pengantar Ekonomi Islam', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Pengantar konsep ekonomi Islam'],
            ['kode_mk' => 'ES-107', 'nama_mk' => 'Pengantar Ilmu Ekonomi', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Dasar-dasar ilmu ekonomi'],
            ['kode_mk' => 'ES-108', 'nama_mk' => 'Fiqih Muamalah', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Hukum Islam dalam transaksi ekonomi'],

            // Semester 2 (20 SKS)
            ['kode_mk' => 'ES-201', 'nama_mk' => 'Bahasa Indonesia', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Mata kuliah wajib nasional bahasa Indonesia'],
            ['kode_mk' => 'ES-202', 'nama_mk' => 'Bahasa Arab II', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab lanjutan'],
            ['kode_mk' => 'ES-203', 'nama_mk' => 'Ekonomi Mikro Syariah', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Teori ekonomi mikro perspektif syariah'],
            ['kode_mk' => 'ES-204', 'nama_mk' => 'Ekonomi Makro Syariah', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Teori ekonomi makro perspektif syariah'],
            ['kode_mk' => 'ES-205', 'nama_mk' => 'Akuntansi Dasar', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Dasar-dasar akuntansi'],
            ['kode_mk' => 'ES-206', 'nama_mk' => 'Ushul Fiqih Ekonomi', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi hukum Islam untuk ekonomi'],
            ['kode_mk' => 'ES-207', 'nama_mk' => 'Sejarah Pemikiran Ekonomi Islam', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Sejarah perkembangan pemikiran ekonomi Islam'],
            ['kode_mk' => 'ES-208', 'nama_mk' => 'Fiqih Muamalah Lanjutan', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Hukum transaksi Islam lanjutan'],

            // Semester 3 (18 SKS)
            ['kode_mk' => 'ES-301', 'nama_mk' => 'Perbankan Syariah', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Sistem perbankan syariah'],
            ['kode_mk' => 'ES-302', 'nama_mk' => 'Akuntansi Syariah', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Akuntansi berbasis syariah'],
            ['kode_mk' => 'ES-303', 'nama_mk' => 'Manajemen Keuangan Syariah', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen keuangan Islam'],
            ['kode_mk' => 'ES-304', 'nama_mk' => 'Statistik Ekonomi', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Statistik untuk analisis ekonomi'],
            ['kode_mk' => 'ES-305', 'nama_mk' => 'Teori Akad Ekonomi Syariah', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Teori kontrak dalam ekonomi syariah'],
            ['kode_mk' => 'ES-306', 'nama_mk' => 'Zakat dan Wakaf', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Pengelolaan zakat, infak, sedekah, dan wakaf'],
            ['kode_mk' => 'ES-307', 'nama_mk' => "Ayat dan Hadits Ekonomi", 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Ayat dan hadits tentang ekonomi'],

            // Semester 4 (20 SKS)
            ['kode_mk' => 'ES-401', 'nama_mk' => 'Lembaga Keuangan Syariah', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Lembaga keuangan berbasis syariah'],
            ['kode_mk' => 'ES-402', 'nama_mk' => 'Pasar Modal Syariah', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Pasar modal syariah di Indonesia'],
            ['kode_mk' => 'ES-403', 'nama_mk' => 'Manajemen Strategis', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen strategis bisnis'],
            ['kode_mk' => 'ES-404', 'nama_mk' => 'Metode Penelitian Kuantitatif', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi penelitian kuantitatif'],
            ['kode_mk' => 'ES-405', 'nama_mk' => 'Ekonomi Pembangunan Islam', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Ekonomi pembangunan perspektif Islam'],
            ['kode_mk' => 'ES-406', 'nama_mk' => 'Keuangan Publik Islam', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Keuangan publik dalam Islam'],
            ['kode_mk' => 'ES-407', 'nama_mk' => 'Etika Bisnis Islam', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Etika berbisnis dalam Islam'],

            // Semester 5 (18 SKS)
            ['kode_mk' => 'ES-501', 'nama_mk' => 'Metode Penelitian Kualitatif', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi penelitian kualitatif'],
            ['kode_mk' => 'ES-502', 'nama_mk' => 'Islamic Entrepreneurship', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Kewirausahaan berbasis syariah'],
            ['kode_mk' => 'ES-503', 'nama_mk' => 'Manajemen Risiko Perbankan Syariah', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen risiko di perbankan syariah'],
            ['kode_mk' => 'ES-504', 'nama_mk' => 'Asuransi Syariah (Takaful)', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Asuransi berbasis syariah'],
            ['kode_mk' => 'ES-505', 'nama_mk' => 'Ekonometrika', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Analisis ekonometrika'],
            ['kode_mk' => 'ES-506', 'nama_mk' => 'Pemasaran Syariah', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Pemasaran berbasis syariah'],

            // Semester 6 (18 SKS)
            ['kode_mk' => 'ES-601', 'nama_mk' => 'Regulasi dan Kebijakan Ekonomi Syariah', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Regulasi ekonomi syariah di Indonesia'],
            ['kode_mk' => 'ES-602', 'nama_mk' => 'Sistem Informasi Manajemen', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'SIM dalam bisnis'],
            ['kode_mk' => 'ES-603', 'nama_mk' => 'Fintech Syariah', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Financial technology berbasis syariah'],
            ['kode_mk' => 'ES-604', 'nama_mk' => 'Audit Syariah', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Audit lembaga keuangan syariah'],
            ['kode_mk' => 'ES-605', 'nama_mk' => 'Seminar Ekonomi Syariah', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Seminar isu ekonomi syariah terkini'],
            ['kode_mk' => 'ES-606', 'nama_mk' => 'Kuliah Kerja Nyata (KKN)', 'sks' => 4, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'KKN di masyarakat'],

            // Semester 7 (18 SKS)
            ['kode_mk' => 'ES-701', 'nama_mk' => 'Praktik Kerja Lapangan (PKL)', 'sks' => 4, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'PKL di lembaga keuangan syariah'],
            ['kode_mk' => 'ES-702', 'nama_mk' => 'Manajemen Investasi Syariah', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen investasi berbasis syariah'],
            ['kode_mk' => 'ES-703', 'nama_mk' => 'Ekonomi Syariah Kontemporer', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Isu ekonomi syariah kontemporer'],
            ['kode_mk' => 'ES-704', 'nama_mk' => 'Studi Kelayakan Bisnis Syariah', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Analisis kelayakan bisnis syariah'],
            ['kode_mk' => 'ES-705', 'nama_mk' => 'Seminar Proposal Skripsi', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Seminar proposal penelitian'],
            ['kode_mk' => 'ES-706', 'nama_mk' => 'Ekonomi Digital Syariah', 'sks' => 3, 'semester' => 7, 'jenis' => 'pilihan', 'deskripsi' => 'Ekonomi digital berbasis syariah'],

            // Semester 8 (6 SKS)
            ['kode_mk' => 'ES-801', 'nama_mk' => 'Skripsi', 'sks' => 6, 'semester' => 8, 'jenis' => 'wajib', 'deskripsi' => 'Penelitian dan penulisan skripsi'],
        ];
    }

    private function getPIAUDMataKuliah()
    {
        return [
            // Semester 1 (18 SKS)
            ['kode_mk' => 'PIAUD-101', 'nama_mk' => 'Pendidikan Pancasila dan Kewarganegaraan', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Mata kuliah wajib nasional tentang Pancasila'],
            ['kode_mk' => 'PIAUD-102', 'nama_mk' => 'Bahasa Inggris', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Inggris untuk akademik'],
            ['kode_mk' => 'PIAUD-103', 'nama_mk' => 'Aqidah', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Kajian akidah Islam'],
            ['kode_mk' => 'PIAUD-104', 'nama_mk' => 'Fiqih Ibadah', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Hukum Islam tentang ibadah'],
            ['kode_mk' => 'PIAUD-105', 'nama_mk' => 'Bahasa Arab I', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab dasar'],
            ['kode_mk' => 'PIAUD-106', 'nama_mk' => 'Pengantar Ilmu Pendidikan', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Dasar-dasar ilmu pendidikan'],
            ['kode_mk' => 'PIAUD-107', 'nama_mk' => 'Dasar-dasar PIAUD', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Dasar pendidikan anak usia dini Islam'],
            ['kode_mk' => 'PIAUD-108', 'nama_mk' => 'Perkembangan Anak Usia Dini', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Tahapan perkembangan anak usia dini'],

            // Semester 2 (19 SKS)
            ['kode_mk' => 'PIAUD-201', 'nama_mk' => 'Bahasa Indonesia', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Mata kuliah wajib nasional bahasa Indonesia'],
            ['kode_mk' => 'PIAUD-202', 'nama_mk' => 'Bahasa Arab II', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab lanjutan'],
            ['kode_mk' => 'PIAUD-203', 'nama_mk' => 'Psikologi Perkembangan Anak', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Psikologi perkembangan anak usia dini'],
            ['kode_mk' => 'PIAUD-204', 'nama_mk' => 'Pembelajaran Anak Usia Dini', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Metode pembelajaran untuk anak usia dini'],
            ['kode_mk' => 'PIAUD-205', 'nama_mk' => 'Pendidikan Agama untuk Anak Usia Dini', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Pendidikan agama Islam untuk anak usia dini'],
            ['kode_mk' => 'PIAUD-206', 'nama_mk' => 'Kesehatan dan Gizi Anak Usia Dini', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Kesehatan dan nutrisi anak usia dini'],
            ['kode_mk' => 'PIAUD-207', 'nama_mk' => 'Media Pembelajaran AUD', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Media pembelajaran untuk anak usia dini'],
            ['kode_mk' => 'PIAUD-208', 'nama_mk' => 'Bermain dan Permainan AUD', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Teori bermain dan permainan edukatif'],

            // Semester 3 (18 SKS)
            ['kode_mk' => 'PIAUD-301', 'nama_mk' => 'Kurikulum PAUD', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Pengembangan kurikulum PAUD'],
            ['kode_mk' => 'PIAUD-302', 'nama_mk' => 'Pembelajaran Bahasa untuk AUD', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Pembelajaran bahasa untuk anak usia dini'],
            ['kode_mk' => 'PIAUD-303', 'nama_mk' => 'Pembelajaran Matematika untuk AUD', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Pembelajaran matematika dasar untuk AUD'],
            ['kode_mk' => 'PIAUD-304', 'nama_mk' => 'Pembelajaran Seni untuk AUD', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Pembelajaran seni untuk anak usia dini'],
            ['kode_mk' => 'PIAUD-305', 'nama_mk' => 'Penilaian dan Asesmen AUD', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Penilaian perkembangan anak usia dini'],
            ['kode_mk' => 'PIAUD-306', 'nama_mk' => 'Bimbingan Konseling untuk AUD', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Bimbingan dan konseling anak usia dini'],
            ['kode_mk' => 'PIAUD-307', 'nama_mk' => "Tahsin Al-Qur'an untuk AUD", 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => "Pembelajaran Al-Qur'an untuk anak usia dini"],

            // Semester 4 (19 SKS)
            ['kode_mk' => 'PIAUD-401', 'nama_mk' => 'Metode Penelitian Kuantitatif', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi penelitian kuantitatif'],
            ['kode_mk' => 'PIAUD-402', 'nama_mk' => 'Pendidikan Karakter Anak Usia Dini', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Pendidikan karakter untuk AUD'],
            ['kode_mk' => 'PIAUD-403', 'nama_mk' => 'Pembelajaran Sains untuk AUD', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Pembelajaran sains untuk anak usia dini'],
            ['kode_mk' => 'PIAUD-404', 'nama_mk' => 'Manajemen Lembaga PAUD', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen lembaga PAUD'],
            ['kode_mk' => 'PIAUD-405', 'nama_mk' => 'Anak Berkebutuhan Khusus', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Pendidikan anak berkebutuhan khusus'],
            ['kode_mk' => 'PIAUD-406', 'nama_mk' => 'Pembelajaran Motorik AUD', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Pengembangan motorik anak usia dini'],
            ['kode_mk' => 'PIAUD-407', 'nama_mk' => 'Hadits dan Sirah untuk AUD', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Pembelajaran hadits dan sirah untuk AUD'],

            // Semester 5 (18 SKS)
            ['kode_mk' => 'PIAUD-501', 'nama_mk' => 'Metode Penelitian Kualitatif', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi penelitian kualitatif'],
            ['kode_mk' => 'PIAUD-502', 'nama_mk' => 'Statistik Pendidikan', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Statistik untuk penelitian pendidikan'],
            ['kode_mk' => 'PIAUD-503', 'nama_mk' => 'Teknologi Pembelajaran AUD', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Teknologi dalam pembelajaran AUD'],
            ['kode_mk' => 'PIAUD-504', 'nama_mk' => 'Pengembangan Kreativitas AUD', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Pengembangan kreativitas anak'],
            ['kode_mk' => 'PIAUD-505', 'nama_mk' => 'Parenting Education', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Pendidikan orangtua'],
            ['kode_mk' => 'PIAUD-506', 'nama_mk' => 'Micro Teaching PAUD', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Praktik mengajar mikro PAUD'],
            ['kode_mk' => 'PIAUD-507', 'nama_mk' => 'Literasi dan Numerasi Awal', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Literasi dan numerasi untuk AUD'],

            // Semester 6 (18 SKS)
            ['kode_mk' => 'PIAUD-601', 'nama_mk' => 'Seminar PAUD', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Seminar isu PAUD terkini'],
            ['kode_mk' => 'PIAUD-602', 'nama_mk' => 'Kewirausahaan PAUD', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Kewirausahaan di bidang PAUD'],
            ['kode_mk' => 'PIAUD-603', 'nama_mk' => 'Penulisan Karya Ilmiah', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Teknik penulisan karya ilmiah'],
            ['kode_mk' => 'PIAUD-604', 'nama_mk' => 'Kurikulum Merdeka untuk AUD', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Implementasi kurikulum merdeka di PAUD'],
            ['kode_mk' => 'PIAUD-605', 'nama_mk' => 'Pembelajaran Berbasis Proyek AUD', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Project-based learning untuk AUD'],
            ['kode_mk' => 'PIAUD-606', 'nama_mk' => 'Kuliah Kerja Nyata (KKN)', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'KKN di masyarakat'],
            ['kode_mk' => 'PIAUD-607', 'nama_mk' => 'Pengembangan Alat Permainan Edukatif', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Pembuatan alat permainan edukatif'],

            // Semester 7 (20 SKS)
            ['kode_mk' => 'PIAUD-701', 'nama_mk' => 'Praktik Pengalaman Lapangan (PPL)', 'sks' => 4, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'PPL di lembaga PAUD'],
            ['kode_mk' => 'PIAUD-702', 'nama_mk' => 'Pengembangan Profesionalisme Guru PAUD', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Profesionalisme guru PAUD'],
            ['kode_mk' => 'PIAUD-703', 'nama_mk' => 'Pembelajaran Digital untuk AUD', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Pembelajaran berbasis teknologi digital'],
            ['kode_mk' => 'PIAUD-704', 'nama_mk' => 'Evaluasi Program PAUD', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Evaluasi program lembaga PAUD'],
            ['kode_mk' => 'PIAUD-705', 'nama_mk' => 'Seminar Proposal Skripsi', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Seminar proposal penelitian'],
            ['kode_mk' => 'PIAUD-706', 'nama_mk' => 'Pendidikan Inklusif AUD', 'sks' => 3, 'semester' => 7, 'jenis' => 'pilihan', 'deskripsi' => 'Pendidikan inklusif untuk anak usia dini'],
            ['kode_mk' => 'PIAUD-707', 'nama_mk' => 'Neurosains untuk Pendidik AUD', 'sks' => 2, 'semester' => 7, 'jenis' => 'pilihan', 'deskripsi' => 'Neurosains dalam pendidikan AUD'],

            // Semester 8 (6 SKS)
            ['kode_mk' => 'PIAUD-801', 'nama_mk' => 'Skripsi', 'sks' => 6, 'semester' => 8, 'jenis' => 'wajib', 'deskripsi' => 'Penelitian dan penulisan skripsi'],
        ];
    }

    private function getMPIMataKuliah()
    {
        return [
            // Semester 1 (18 SKS)
            ['kode_mk' => 'MPI-101', 'nama_mk' => 'Pendidikan Pancasila dan Kewarganegaraan', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Mata kuliah wajib nasional tentang Pancasila'],
            ['kode_mk' => 'MPI-102', 'nama_mk' => 'Bahasa Inggris', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Inggris untuk akademik'],
            ['kode_mk' => 'MPI-103', 'nama_mk' => 'Aqidah', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Kajian akidah Islam'],
            ['kode_mk' => 'MPI-104', 'nama_mk' => 'Fiqih Ibadah', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Hukum Islam tentang ibadah'],
            ['kode_mk' => 'MPI-105', 'nama_mk' => 'Bahasa Arab I', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab dasar'],
            ['kode_mk' => 'MPI-106', 'nama_mk' => 'Pengantar Ilmu Pendidikan', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Dasar-dasar ilmu pendidikan'],
            ['kode_mk' => 'MPI-107', 'nama_mk' => 'Pengantar Manajemen Pendidikan', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Konsep dasar manajemen pendidikan'],
            ['kode_mk' => 'MPI-108', 'nama_mk' => 'Ilmu Pendidikan Islam', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Konsep dan teori pendidikan Islam'],

            // Semester 2 (19 SKS)
            ['kode_mk' => 'MPI-201', 'nama_mk' => 'Bahasa Indonesia', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Mata kuliah wajib nasional bahasa Indonesia'],
            ['kode_mk' => 'MPI-202', 'nama_mk' => 'Bahasa Arab II', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab lanjutan'],
            ['kode_mk' => 'MPI-203', 'nama_mk' => 'Psikologi Pendidikan', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Psikologi dalam pendidikan'],
            ['kode_mk' => 'MPI-204', 'nama_mk' => 'Manajemen Kurikulum', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen pengembangan kurikulum'],
            ['kode_mk' => 'MPI-205', 'nama_mk' => 'Administrasi Pendidikan', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Administrasi lembaga pendidikan'],
            ['kode_mk' => 'MPI-206', 'nama_mk' => 'Sejarah Pendidikan Islam', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Sejarah perkembangan pendidikan Islam'],
            ['kode_mk' => 'MPI-207', 'nama_mk' => 'Komunikasi Pendidikan', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Komunikasi dalam konteks pendidikan'],
            ['kode_mk' => 'MPI-208', 'nama_mk' => 'Fiqih Muamalah', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Hukum Islam dalam transaksi'],

            // Semester 3 (18 SKS)
            ['kode_mk' => 'MPI-301', 'nama_mk' => 'Manajemen Peserta Didik', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen kesiswaan'],
            ['kode_mk' => 'MPI-302', 'nama_mk' => 'Manajemen Tenaga Pendidik', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen SDM pendidikan'],
            ['kode_mk' => 'MPI-303', 'nama_mk' => 'Supervisi Pendidikan', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Supervisi dan pengawasan pendidikan'],
            ['kode_mk' => 'MPI-304', 'nama_mk' => 'Manajemen Sarana Prasarana Pendidikan', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen sarana dan prasarana'],
            ['kode_mk' => 'MPI-305', 'nama_mk' => 'Statistik Pendidikan', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Statistik untuk penelitian pendidikan'],
            ['kode_mk' => 'MPI-306', 'nama_mk' => 'Kepemimpinan Pendidikan', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Kepemimpinan dalam lembaga pendidikan'],
            ['kode_mk' => 'MPI-307', 'nama_mk' => 'Metodologi Studi Islam', 'sks' => 1, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi dalam studi Islam'],

            // Semester 4 (20 SKS)
            ['kode_mk' => 'MPI-401', 'nama_mk' => 'Manajemen Keuangan Pendidikan', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen keuangan lembaga pendidikan'],
            ['kode_mk' => 'MPI-402', 'nama_mk' => 'Metode Penelitian Kuantitatif', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi penelitian kuantitatif'],
            ['kode_mk' => 'MPI-403', 'nama_mk' => 'Manajemen Berbasis Sekolah (MBS)', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Konsep dan implementasi MBS'],
            ['kode_mk' => 'MPI-404', 'nama_mk' => 'Evaluasi Program Pendidikan', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Evaluasi program lembaga pendidikan'],
            ['kode_mk' => 'MPI-405', 'nama_mk' => 'Filsafat Pendidikan Islam', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Filsafat pendidikan dalam Islam'],
            ['kode_mk' => 'MPI-406', 'nama_mk' => 'Manajemen Hubungan Masyarakat', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen humas lembaga pendidikan'],
            ['kode_mk' => 'MPI-407', 'nama_mk' => 'Tafsir Tarbawi', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Tafsir ayat-ayat pendidikan'],

            // Semester 5 (18 SKS)
            ['kode_mk' => 'MPI-501', 'nama_mk' => 'Metode Penelitian Kualitatif', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi penelitian kualitatif'],
            ['kode_mk' => 'MPI-502', 'nama_mk' => 'Sistem Informasi Manajemen Pendidikan', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'SIM untuk lembaga pendidikan'],
            ['kode_mk' => 'MPI-503', 'nama_mk' => 'Manajemen Mutu Pendidikan', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen mutu dan akreditasi'],
            ['kode_mk' => 'MPI-504', 'nama_mk' => 'Perencanaan Pendidikan', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Perencanaan strategis pendidikan'],
            ['kode_mk' => 'MPI-505', 'nama_mk' => 'Manajemen Konflik dalam Organisasi Pendidikan', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen konflik organisasi'],
            ['kode_mk' => 'MPI-506', 'nama_mk' => 'Bimbingan Konseling', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Bimbingan dan konseling di sekolah'],
            ['kode_mk' => 'MPI-507', 'nama_mk' => 'Hadits Tarbawi', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Hadits-hadits tentang pendidikan'],

            // Semester 6 (18 SKS)
            ['kode_mk' => 'MPI-601', 'nama_mk' => 'Kebijakan Pendidikan', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Kebijakan pendidikan nasional'],
            ['kode_mk' => 'MPI-602', 'nama_mk' => 'Manajemen Pendidikan Karakter', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen pendidikan karakter'],
            ['kode_mk' => 'MPI-603', 'nama_mk' => 'Pengembangan Organisasi Pendidikan', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Pengembangan organisasi sekolah'],
            ['kode_mk' => 'MPI-604', 'nama_mk' => 'Seminar Manajemen Pendidikan Islam', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Seminar isu manajemen pendidikan'],
            ['kode_mk' => 'MPI-605', 'nama_mk' => 'Teknologi Informasi dan Komunikasi', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'TIK dalam manajemen pendidikan'],
            ['kode_mk' => 'MPI-606', 'nama_mk' => 'Penulisan Karya Ilmiah', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Teknik penulisan karya ilmiah'],
            ['kode_mk' => 'MPI-607', 'nama_mk' => 'Kuliah Kerja Nyata (KKN)', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'KKN di masyarakat'],

            // Semester 7 (19 SKS)
            ['kode_mk' => 'MPI-701', 'nama_mk' => 'Praktik Kerja Lapangan (PKL)', 'sks' => 4, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'PKL di lembaga pendidikan'],
            ['kode_mk' => 'MPI-702', 'nama_mk' => 'Manajemen Strategi Pendidikan', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen strategi lembaga pendidikan'],
            ['kode_mk' => 'MPI-703', 'nama_mk' => 'Entrepreneurship Pendidikan', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Kewirausahaan di bidang pendidikan'],
            ['kode_mk' => 'MPI-704', 'nama_mk' => 'Manajemen Perubahan dalam Organisasi Pendidikan', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Change management dalam pendidikan'],
            ['kode_mk' => 'MPI-705', 'nama_mk' => 'Seminar Proposal Skripsi', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Seminar proposal penelitian'],
            ['kode_mk' => 'MPI-706', 'nama_mk' => 'Manajemen Pendidikan Inklusif', 'sks' => 2, 'semester' => 7, 'jenis' => 'pilihan', 'deskripsi' => 'Manajemen pendidikan inklusif'],
            ['kode_mk' => 'MPI-707', 'nama_mk' => 'Digitalisasi Manajemen Pendidikan', 'sks' => 2, 'semester' => 7, 'jenis' => 'pilihan', 'deskripsi' => 'Digitalisasi manajemen sekolah'],

            // Semester 8 (6 SKS)
            ['kode_mk' => 'MPI-801', 'nama_mk' => 'Skripsi', 'sks' => 6, 'semester' => 8, 'jenis' => 'wajib', 'deskripsi' => 'Penelitian dan penulisan skripsi'],
        ];
    }
}
