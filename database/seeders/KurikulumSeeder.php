<?php

namespace Database\Seeders;

use App\Models\Kurikulum;
use App\Models\ProgramStudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KurikulumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programStudis = ProgramStudi::all();

        foreach ($programStudis as $prodi) {
            Kurikulum::create([
                'program_studi_id' => $prodi->id,
                'nama_kurikulum' => "Kurikulum {$prodi->nama_prodi} 2024",
                'tahun_mulai' => 2024,
                'tahun_selesai' => null,
                'is_active' => true,
                'total_sks' => $prodi->jenjang === 'D3' ? 110 : 144,
            ]);
        }
    }
}
