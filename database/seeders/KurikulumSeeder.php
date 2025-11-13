<?php

namespace Database\Seeders;

use App\Models\Kurikulum;
use App\Models\ProgramStudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KurikulumSeeder extends Seeder
{
    /**
     * STAI AL-FATIH Tangerang - Kurikulum Seeder
     * Kurikulum 2024 untuk semua Program Studi S1
     */
    public function run(): void
    {
        $programStudis = ProgramStudi::all();

        foreach ($programStudis as $prodi) {
            Kurikulum::firstOrCreate(
                [
                    'program_studi_id' => $prodi->id,
                    'tahun_mulai' => 2024,
                ],
                [
                    'nama_kurikulum' => "Kurikulum {$prodi->nama_prodi} 2024",
                    'tahun_selesai' => null,
                    'is_active' => true,
                    'total_sks' => 144, // Standard S1 = 144 SKS
                ]
            );
        }

        $this->command->info('✓ Kurikulum 2024 created for all program studi (144 SKS)');
    }
}
