<?php

namespace Database\Seeders;

use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaiAlfatihMataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeder for STAI AL-FATIH Tangerang Mata Kuliah (Courses)
     * Source: https://staialfatih.or.id/
     *
     * Programs covered:
     * 1. Ilmu Al-Qur'an dan Tafsir (IQT) - S1, S2, S3
     * 2. Hukum Ekonomi Syariah (HES) - S1, S2, S3
     *
     * Both Daring (Online) and Luring (Offline) modes
     *
     * @return void
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // Get all STAI AL-FATIH program studis with their kurikulums
            $programStudis = ProgramStudi::whereIn('kode_prodi', [
                'IQT-D-S1', 'IQT-D-S2', 'IQT-D-S3',
                'IQT-L-S1', 'IQT-L-S2', 'IQT-L-S3',
                'HES-D-S1', 'HES-D-S2', 'HES-D-S3',
                'HES-L-S1', 'HES-L-S2', 'HES-L-S3',
            ])->get();

            foreach ($programStudis as $prodi) {
                // Get or create kurikulum for this program
                $kurikulum = Kurikulum::firstOrCreate(
                    [
                        'program_studi_id' => $prodi->id,
                    ],
                    [
                        'nama_kurikulum' => "Kurikulum {$prodi->nama_prodi} 2024",
                        'tahun_mulai' => 2024,
                        'tahun_selesai' => null,
                        'is_active' => true,
                        'total_sks' => $this->getTotalSks($prodi->jenjang),
                    ]
                );

                // Determine base program type
                $isIQT = str_contains($prodi->kode_prodi, 'IQT');
                $isHES = str_contains($prodi->kode_prodi, 'HES');
                $jenjang = $prodi->jenjang;

                // Get mata kuliah based on program type and level
                $mataKuliahs = $this->getMataKuliah($isIQT ? 'IQT' : 'HES', $jenjang, $prodi->kode_prodi);

                // Insert mata kuliah
                foreach ($mataKuliahs as $mk) {
                    $existing = MataKuliah::where('kode_mk', $mk['kode_mk'])
                        ->where('kurikulum_id', $kurikulum->id)
                        ->first();

                    if (!$existing) {
                        MataKuliah::create(array_merge($mk, [
                            'kurikulum_id' => $kurikulum->id,
                        ]));
                    }
                }

                $this->command->info("Seeded mata kuliah for: {$prodi->nama_prodi} ({$prodi->jenjang})");
            }

            DB::commit();
            $this->command->info('STAI AL-FATIH Mata Kuliah seeder completed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding STAI AL-FATIH mata kuliah: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get total SKS based on jenjang
     */
    private function getTotalSks(string $jenjang): int
    {
        return match($jenjang) {
            'S1' => 144, // 4 years / 8 semesters
            'S2' => 48,  // 2 years / 4 semesters
            'S3' => 48,  // 3 years / 6 semesters (minimum)
            default => 144,
        };
    }

    /**
     * Get mata kuliah data based on program and level
     */
    private function getMataKuliah(string $program, string $jenjang, string $kodeProdi): array
    {
        if ($program === 'IQT') {
            return match($jenjang) {
                'S1' => $this->getIQTS1MataKuliah($kodeProdi),
                'S2' => $this->getIQTS2MataKuliah($kodeProdi),
                'S3' => $this->getIQTS3MataKuliah($kodeProdi),
                default => [],
            };
        } else { // HES
            return match($jenjang) {
                'S1' => $this->getHESS1MataKuliah($kodeProdi),
                'S2' => $this->getHESS2MataKuliah($kodeProdi),
                'S3' => $this->getHESS3MataKuliah($kodeProdi),
                default => [],
            };
        }
    }

    /**
     * Ilmu Al-Qur'an dan Tafsir - S1 Mata Kuliah
     */
    private function getIQTS1MataKuliah(string $kodeProdi): array
    {
        return [
            // Semester 1
            ['kode_mk' => "{$kodeProdi}-101", 'nama_mk' => "Ulumul Qur'an", 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => "Ilmu-ilmu Al-Qur'an"],
            ['kode_mk' => "{$kodeProdi}-102", 'nama_mk' => 'Bahasa Arab I', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => "Bahasa Arab dasar untuk studi Al-Qur'an"],
            ['kode_mk' => "{$kodeProdi}-103", 'nama_mk' => "Tahsin Al-Qur'an", 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => "Perbaikan bacaan Al-Qur'an"],
            ['kode_mk' => "{$kodeProdi}-104", 'nama_mk' => 'Aqidah Islamiyah', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Studi aqidah Islam'],
            ['kode_mk' => "{$kodeProdi}-105", 'nama_mk' => 'Pendidikan Pancasila', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Pendidikan Pancasila dan Kewarganegaraan'],
            ['kode_mk' => "{$kodeProdi}-106", 'nama_mk' => 'Bahasa Indonesia', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Indonesia akademik'],

            // Semester 2
            ['kode_mk' => "{$kodeProdi}-201", 'nama_mk' => "Tafsir Maudhu'i", 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => "Tafsir tematik Al-Qur'an"],
            ['kode_mk' => "{$kodeProdi}-202", 'nama_mk' => 'Bahasa Arab II', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab lanjutan'],
            ['kode_mk' => "{$kodeProdi}-203", 'nama_mk' => 'Tajwid', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Ilmu tajwid'],
            ['kode_mk' => "{$kodeProdi}-204", 'nama_mk' => 'Hadits I', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Pengantar ilmu hadits'],
            ['kode_mk' => "{$kodeProdi}-205", 'nama_mk' => 'Sejarah Peradaban Islam', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Sejarah peradaban Islam'],
            ['kode_mk' => "{$kodeProdi}-206", 'nama_mk' => 'Bahasa Inggris', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Inggris akademik'],

            // Semester 3
            ['kode_mk' => "{$kodeProdi}-301", 'nama_mk' => 'Tafsir Tahlili I', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Tafsir analitis juz 1-10'],
            ['kode_mk' => "{$kodeProdi}-302", 'nama_mk' => "Qira'at", 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => "Ilmu qira'at sab'ah"],
            ['kode_mk' => "{$kodeProdi}-303", 'nama_mk' => 'Nahwu I', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Tata bahasa Arab - Nahwu dasar'],
            ['kode_mk' => "{$kodeProdi}-304", 'nama_mk' => 'Hadits II', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Ilmu hadits lanjutan'],
            ['kode_mk' => "{$kodeProdi}-305", 'nama_mk' => 'Fiqh I', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Fiqh ibadah'],
            ['kode_mk' => "{$kodeProdi}-306", 'nama_mk' => 'Metodologi Penelitian', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi penelitian kualitatif dan kuantitatif'],

            // Semester 4
            ['kode_mk' => "{$kodeProdi}-401", 'nama_mk' => 'Tafsir Tahlili II', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Tafsir analitis juz 11-20'],
            ['kode_mk' => "{$kodeProdi}-402", 'nama_mk' => 'Musthalah Hadits', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Istilah-istilah dalam ilmu hadits'],
            ['kode_mk' => "{$kodeProdi}-403", 'nama_mk' => 'Nahwu II', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Tata bahasa Arab - Nahwu lanjutan'],
            ['kode_mk' => "{$kodeProdi}-404", 'nama_mk' => 'Sharaf I', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Morfologi bahasa Arab dasar'],
            ['kode_mk' => "{$kodeProdi}-405", 'nama_mk' => 'Fiqh II', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Fiqh muamalah'],
            ['kode_mk' => "{$kodeProdi}-406", 'nama_mk' => 'Ushul Fiqh I', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Dasar-dasar ushul fiqh'],

            // Semester 5
            ['kode_mk' => "{$kodeProdi}-501", 'nama_mk' => 'Tafsir Tahlili III', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Tafsir analitis juz 21-30'],
            ['kode_mk' => "{$kodeProdi}-502", 'nama_mk' => 'Asbabun Nuzul', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => "Sebab-sebab turunnya ayat Al-Qur'an"],
            ['kode_mk' => "{$kodeProdi}-503", 'nama_mk' => 'Sharaf II', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Morfologi bahasa Arab lanjutan'],
            ['kode_mk' => "{$kodeProdi}-504", 'nama_mk' => "I'jaz Al-Qur'an", 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => "Kemukjizatan Al-Qur'an"],
            ['kode_mk' => "{$kodeProdi}-505", 'nama_mk' => 'Ushul Fiqh II', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Ushul fiqh lanjutan'],
            ['kode_mk' => "{$kodeProdi}-506", 'nama_mk' => 'Balaghah', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => "Ilmu balaghah (Ma'ani, Bayan, Badi)"],

            // Semester 6
            ['kode_mk' => "{$kodeProdi}-601", 'nama_mk' => "Tafsir bi al-Ma'tsur", 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Tafsir dengan riwayat'],
            ['kode_mk' => "{$kodeProdi}-602", 'nama_mk' => "Tafsir bi ar-Ra'yi", 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Tafsir dengan pendapat/ijtihad'],
            ['kode_mk' => "{$kodeProdi}-603", 'nama_mk' => 'Nasikh Mansukh', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Ayat-ayat yang menasikh dan mansukh'],
            ['kode_mk' => "{$kodeProdi}-604", 'nama_mk' => 'Manhaj Mufassirin', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Metode para mufassir'],
            ['kode_mk' => "{$kodeProdi}-605", 'nama_mk' => 'Tasawuf', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Ilmu tasawuf dan akhlak'],
            ['kode_mk' => "{$kodeProdi}-606", 'nama_mk' => 'Ushul Tafsir', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Dasar-dasar ilmu tafsir'],

            // Semester 7
            ['kode_mk' => "{$kodeProdi}-701", 'nama_mk' => 'Tafsir Kontemporer', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Tafsir kontemporer dan metodologinya'],
            ['kode_mk' => "{$kodeProdi}-702", 'nama_mk' => 'Studi Kitab Tafsir Klasik', 'sks' => 3, 'semester' => 7, 'jenis' => 'pilihan', 'deskripsi' => 'Kajian kitab tafsir klasik (Tabari, Ibnu Katsir, dll)'],
            ['kode_mk' => "{$kodeProdi}-703", 'nama_mk' => 'Tafsir Indonesia', 'sks' => 2, 'semester' => 7, 'jenis' => 'pilihan', 'deskripsi' => 'Tafsir karya ulama Indonesia'],
            ['kode_mk' => "{$kodeProdi}-704", 'nama_mk' => "Tahfizh Al-Qur'an", 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => "Program hafalan Al-Qur'an"],
            ['kode_mk' => "{$kodeProdi}-705", 'nama_mk' => 'Seminar Proposal', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Seminar proposal skripsi'],
            ['kode_mk' => "{$kodeProdi}-706", 'nama_mk' => 'KKN (Kuliah Kerja Nyata)', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Kuliah Kerja Nyata'],

            // Semester 8
            ['kode_mk' => "{$kodeProdi}-801", 'nama_mk' => 'Skripsi', 'sks' => 6, 'semester' => 8, 'jenis' => 'wajib', 'deskripsi' => 'Penelitian tugas akhir S1'],
        ];
    }

    /**
     * Ilmu Al-Qur'an dan Tafsir - S2 Mata Kuliah
     */
    private function getIQTS2MataKuliah(string $kodeProdi): array
    {
        return [
            // Semester 1
            ['kode_mk' => "{$kodeProdi}-101", 'nama_mk' => 'Metodologi Penelitian Lanjutan', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi penelitian tingkat master'],
            ['kode_mk' => "{$kodeProdi}-102", 'nama_mk' => 'Filsafat Ilmu', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Filsafat ilmu pengetahuan'],
            ['kode_mk' => "{$kodeProdi}-103", 'nama_mk' => "Studi Al-Qur'an Kontemporer", 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => "Kajian Al-Qur'an kontemporer"],
            ['kode_mk' => "{$kodeProdi}-104", 'nama_mk' => "Hermeneutika Al-Qur'an", 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Hermeneutika dalam tafsir'],

            // Semester 2
            ['kode_mk' => "{$kodeProdi}-201", 'nama_mk' => 'Tafsir Tarbawi', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Tafsir pendidikan'],
            ['kode_mk' => "{$kodeProdi}-202", 'nama_mk' => 'Tafsir Sosial Kemasyarakatan', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Tafsir dalam konteks sosial'],
            ['kode_mk' => "{$kodeProdi}-203", 'nama_mk' => 'Tafsir Ekonomi', 'sks' => 3, 'semester' => 2, 'jenis' => 'pilihan', 'deskripsi' => 'Tafsir ayat-ayat ekonomi'],
            ['kode_mk' => "{$kodeProdi}-204", 'nama_mk' => 'Tafsir Hukum', 'sks' => 3, 'semester' => 2, 'jenis' => 'pilihan', 'deskripsi' => 'Tafsir ayat-ayat hukum (ahkam)'],

            // Semester 3
            ['kode_mk' => "{$kodeProdi}-301", 'nama_mk' => "Studi Kitab Tafsir Mu'tabarah", 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => "Kajian kitab tafsir mu'tabarah"],
            ['kode_mk' => "{$kodeProdi}-302", 'nama_mk' => 'Tafsir Nusantara', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Tafsir karya ulama Nusantara'],
            ['kode_mk' => "{$kodeProdi}-303", 'nama_mk' => 'Seminar Proposal Tesis', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Seminar proposal tesis'],
            ['kode_mk' => "{$kodeProdi}-304", 'nama_mk' => "Living Qur'an", 'sks' => 3, 'semester' => 3, 'jenis' => 'pilihan', 'deskripsi' => "Al-Qur'an dalam kehidupan masyarakat"],

            // Semester 4
            ['kode_mk' => "{$kodeProdi}-401", 'nama_mk' => 'Tesis', 'sks' => 6, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Penelitian tugas akhir S2'],
        ];
    }

    /**
     * Ilmu Al-Qur'an dan Tafsir - S3 Mata Kuliah
     */
    private function getIQTS3MataKuliah(string $kodeProdi): array
    {
        return [
            // Semester 1
            ['kode_mk' => "{$kodeProdi}-101", 'nama_mk' => 'Epistemologi Tafsir', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Epistemologi ilmu tafsir'],
            ['kode_mk' => "{$kodeProdi}-102", 'nama_mk' => 'Metodologi Penelitian Doktoral', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi penelitian tingkat doktor'],
            ['kode_mk' => "{$kodeProdi}-103", 'nama_mk' => 'Kritik Tafsir', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Kritik metodologi tafsir'],

            // Semester 2
            ['kode_mk' => "{$kodeProdi}-201", 'nama_mk' => 'Tafsir Lintas Mazhab', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Perbandingan tafsir antar mazhab'],
            ['kode_mk' => "{$kodeProdi}-202", 'nama_mk' => 'Tafsir dan Konteks Keindonesiaan', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Tafsir dalam konteks Indonesia'],
            ['kode_mk' => "{$kodeProdi}-203", 'nama_mk' => 'Seminar Lanjutan', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Seminar akademik lanjutan'],

            // Semester 3
            ['kode_mk' => "{$kodeProdi}-301", 'nama_mk' => 'Seminar Proposal Disertasi', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Seminar proposal disertasi'],

            // Semester 4-6
            ['kode_mk' => "{$kodeProdi}-401", 'nama_mk' => 'Disertasi', 'sks' => 12, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Penelitian tugas akhir S3'],
        ];
    }

    /**
     * Hukum Ekonomi Syariah - S1 Mata Kuliah
     * Based on actual HES curriculum from staialfatih.or.id
     */
    private function getHESS1MataKuliah(string $kodeProdi): array
    {
        return [
            // Semester 1
            ['kode_mk' => "{$kodeProdi}-MPK-101", 'nama_mk' => 'Bahasa Indonesia', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Mata Kuliah Pengembangan Kepribadian - Bahasa Indonesia'],
            ['kode_mk' => "{$kodeProdi}-MPK-102", 'nama_mk' => 'Bahasa Inggris', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Mata Kuliah Pengembangan Kepribadian - Bahasa Inggris'],
            ['kode_mk' => "{$kodeProdi}-MPK-103", 'nama_mk' => 'Pendidikan Pancasila', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Mata Kuliah Pengembangan Kepribadian - Pancasila'],
            ['kode_mk' => "{$kodeProdi}-MPB-101", 'nama_mk' => 'Aqidah Akhlak', 'sks' => 2, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Mata Kuliah Pengembangan Berkehidupan Bermasyarakat'],
            ['kode_mk' => "{$kodeProdi}-MKB-101", 'nama_mk' => 'Pengantar Bisnis Syariah', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Mata Kuliah Keahlian Berkarya - Bisnis Syariah'],
            ['kode_mk' => "{$kodeProdi}-MKB-102", 'nama_mk' => 'Ekonomi Mikro Syariah', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Mata Kuliah Keahlian Berkarya - Ekonomi Mikro'],

            // Semester 2
            ['kode_mk' => "{$kodeProdi}-MPK-201", 'nama_mk' => 'Pendidikan Kewarganegaraan', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Mata Kuliah Pengembangan Kepribadian - PKN'],
            ['kode_mk' => "{$kodeProdi}-MPB-201", 'nama_mk' => "Ulumul Qur'an", 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => "Ilmu-ilmu Al-Qur'an"],
            ['kode_mk' => "{$kodeProdi}-MKK-201", 'nama_mk' => 'Sejarah Hukum', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Mata Kuliah Keilmuan dan Keterampilan - Sejarah Hukum'],
            ['kode_mk' => "{$kodeProdi}-MKB-201", 'nama_mk' => 'Ekonomi Makro Syariah', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Mata Kuliah Keahlian Berkarya - Ekonomi Makro'],
            ['kode_mk' => "{$kodeProdi}-MKB-202", 'nama_mk' => 'Bahasa Arab I', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab Dasar'],

            // Semester 3
            ['kode_mk' => "{$kodeProdi}-MPB-301", 'nama_mk' => 'Ulumul Hadits', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Ilmu-ilmu Hadits'],
            ['kode_mk' => "{$kodeProdi}-MKK-301", 'nama_mk' => 'Pengantar Ilmu Hukum', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Dasar-dasar ilmu hukum'],
            ['kode_mk' => "{$kodeProdi}-MKB-301", 'nama_mk' => 'Hukum Ziswaf', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Hukum Zakat, Infak, Sedekah, Wakaf'],
            ['kode_mk' => "{$kodeProdi}-MKB-302", 'nama_mk' => 'Bank dan Lembaga Keuangan Syariah', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Bank dan LKS'],
            ['kode_mk' => "{$kodeProdi}-MKB-303", 'nama_mk' => 'Bahasa Arab II', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Bahasa Arab Lanjutan'],

            // Semester 4
            ['kode_mk' => "{$kodeProdi}-MPB-401", 'nama_mk' => 'Sejarah Peradaban Islam', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Sejarah peradaban Islam'],
            ['kode_mk' => "{$kodeProdi}-MKK-401", 'nama_mk' => 'Fiqh Muamalah', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Fiqh transaksi ekonomi'],
            ['kode_mk' => "{$kodeProdi}-MKB-401", 'nama_mk' => 'Hukum Ketenagakerjaan', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Hukum ketenagakerjaan Islam'],
            ['kode_mk' => "{$kodeProdi}-MKB-402", 'nama_mk' => 'Pasar Modal Syariah', 'sks' => 3, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Pasar modal berbasis syariah'],
            ['kode_mk' => "{$kodeProdi}-MKB-403", 'nama_mk' => 'Metodologi Penelitian', 'sks' => 2, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi penelitian hukum'],

            // Semester 5
            ['kode_mk' => "{$kodeProdi}-MPB-501", 'nama_mk' => 'Ushul Fiqh', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Dasar-dasar ushul fiqh'],
            ['kode_mk' => "{$kodeProdi}-MKK-501", 'nama_mk' => 'Hukum Bisnis', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Hukum bisnis syariah'],
            ['kode_mk' => "{$kodeProdi}-MKB-501", 'nama_mk' => 'Hukum Perbankan Syariah', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Hukum perbankan syariah'],
            ['kode_mk' => "{$kodeProdi}-MKB-502", 'nama_mk' => 'Asuransi Syariah', 'sks' => 2, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Takaful dan asuransi syariah'],
            ['kode_mk' => "{$kodeProdi}-MKB-503", 'nama_mk' => 'Akuntansi Syariah', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib', 'deskripsi' => 'Akuntansi berbasis syariah'],

            // Semester 6
            ['kode_mk' => "{$kodeProdi}-MKK-601", 'nama_mk' => 'Hukum Waris Islam', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Hukum waris Islam (faraid)'],
            ['kode_mk' => "{$kodeProdi}-MKB-601", 'nama_mk' => 'Hukum Perikatan Syariah', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Hukum perikatan dalam Islam'],
            ['kode_mk' => "{$kodeProdi}-MKB-602", 'nama_mk' => 'Fintech Syariah', 'sks' => 2, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Financial Technology Syariah'],
            ['kode_mk' => "{$kodeProdi}-MKB-603", 'nama_mk' => 'Hukum Perdata Islam', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Hukum perdata Islam'],
            ['kode_mk' => "{$kodeProdi}-MKB-604", 'nama_mk' => 'Manajemen Keuangan Syariah', 'sks' => 3, 'semester' => 6, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen keuangan Islam'],

            // Semester 7
            ['kode_mk' => "{$kodeProdi}-MKB-701", 'nama_mk' => 'Hukum Perusahaan', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Hukum perusahaan syariah'],
            ['kode_mk' => "{$kodeProdi}-MKB-702", 'nama_mk' => 'Hukum Agraria Islam', 'sks' => 2, 'semester' => 7, 'jenis' => 'pilihan', 'deskripsi' => 'Hukum agraria dalam Islam'],
            ['kode_mk' => "{$kodeProdi}-MKB-703", 'nama_mk' => 'Etika Bisnis Islam', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Etika bisnis dalam Islam'],
            ['kode_mk' => "{$kodeProdi}-MKB-704", 'nama_mk' => 'Praktik Kerja Lapangan (PKL)', 'sks' => 4, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Magang di lembaga keuangan syariah'],
            ['kode_mk' => "{$kodeProdi}-MKB-705", 'nama_mk' => 'Seminar Proposal', 'sks' => 2, 'semester' => 7, 'jenis' => 'wajib', 'deskripsi' => 'Seminar proposal skripsi'],

            // Semester 8
            ['kode_mk' => "{$kodeProdi}-MKB-801", 'nama_mk' => 'Tugas Akhir (Skripsi)', 'sks' => 6, 'semester' => 8, 'jenis' => 'wajib', 'deskripsi' => 'Penelitian tugas akhir S1'],
        ];
    }

    /**
     * Hukum Ekonomi Syariah - S2 Mata Kuliah
     */
    private function getHESS2MataKuliah(string $kodeProdi): array
    {
        return [
            // Semester 1
            ['kode_mk' => "{$kodeProdi}-101", 'nama_mk' => 'Metodologi Penelitian Hukum', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi penelitian hukum Islam'],
            ['kode_mk' => "{$kodeProdi}-102", 'nama_mk' => 'Filsafat Hukum Islam', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Filsafat hukum Islam'],
            ['kode_mk' => "{$kodeProdi}-103", 'nama_mk' => 'Ushul Fiqh Lanjutan', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Ushul fiqh tingkat master'],
            ['kode_mk' => "{$kodeProdi}-104", 'nama_mk' => 'Ekonomi Islam Kontemporer', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Isu ekonomi Islam kontemporer'],

            // Semester 2
            ['kode_mk' => "{$kodeProdi}-201", 'nama_mk' => 'Hukum Perbankan Syariah Lanjutan', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Hukum perbankan syariah tingkat lanjut'],
            ['kode_mk' => "{$kodeProdi}-202", 'nama_mk' => 'Hukum Pasar Modal Syariah', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Regulasi pasar modal syariah'],
            ['kode_mk' => "{$kodeProdi}-203", 'nama_mk' => 'Manajemen Risiko Lembaga Keuangan Syariah', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Manajemen risiko LKS'],
            ['kode_mk' => "{$kodeProdi}-204", 'nama_mk' => 'Hukum Bisnis Internasional', 'sks' => 3, 'semester' => 2, 'jenis' => 'pilihan', 'deskripsi' => 'Hukum bisnis internasional syariah'],

            // Semester 3
            ['kode_mk' => "{$kodeProdi}-301", 'nama_mk' => 'Kebijakan Ekonomi Syariah', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Kebijakan ekonomi berbasis syariah'],
            ['kode_mk' => "{$kodeProdi}-302", 'nama_mk' => 'Tata Kelola Lembaga Keuangan Syariah', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Good governance LKS'],
            ['kode_mk' => "{$kodeProdi}-303", 'nama_mk' => 'Seminar Proposal Tesis', 'sks' => 2, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Seminar proposal tesis'],
            ['kode_mk' => "{$kodeProdi}-304", 'nama_mk' => 'Hukum Ziswaf Modern', 'sks' => 2, 'semester' => 3, 'jenis' => 'pilihan', 'deskripsi' => 'Pengelolaan ziswaf modern'],

            // Semester 4
            ['kode_mk' => "{$kodeProdi}-401", 'nama_mk' => 'Tesis', 'sks' => 6, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Penelitian tugas akhir S2'],
        ];
    }

    /**
     * Hukum Ekonomi Syariah - S3 Mata Kuliah
     */
    private function getHESS3MataKuliah(string $kodeProdi): array
    {
        return [
            // Semester 1
            ['kode_mk' => "{$kodeProdi}-101", 'nama_mk' => 'Epistemologi Hukum Islam', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Epistemologi hukum Islam'],
            ['kode_mk' => "{$kodeProdi}-102", 'nama_mk' => 'Metodologi Penelitian Doktoral', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Metodologi penelitian tingkat doktor'],
            ['kode_mk' => "{$kodeProdi}-103", 'nama_mk' => 'Teori Hukum Ekonomi Islam', 'sks' => 3, 'semester' => 1, 'jenis' => 'wajib', 'deskripsi' => 'Teori hukum ekonomi Islam'],

            // Semester 2
            ['kode_mk' => "{$kodeProdi}-201", 'nama_mk' => 'Kritik Ekonomi Islam', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Kritik terhadap ekonomi Islam'],
            ['kode_mk' => "{$kodeProdi}-202", 'nama_mk' => 'Hukum Ekonomi Global', 'sks' => 3, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Hukum ekonomi dalam konteks global'],
            ['kode_mk' => "{$kodeProdi}-203", 'nama_mk' => 'Seminar Lanjutan', 'sks' => 2, 'semester' => 2, 'jenis' => 'wajib', 'deskripsi' => 'Seminar akademik lanjutan'],

            // Semester 3
            ['kode_mk' => "{$kodeProdi}-301", 'nama_mk' => 'Seminar Proposal Disertasi', 'sks' => 3, 'semester' => 3, 'jenis' => 'wajib', 'deskripsi' => 'Seminar proposal disertasi'],

            // Semester 4-6
            ['kode_mk' => "{$kodeProdi}-401", 'nama_mk' => 'Disertasi', 'sks' => 12, 'semester' => 4, 'jenis' => 'wajib', 'deskripsi' => 'Penelitian tugas akhir S3'],
        ];
    }
}
