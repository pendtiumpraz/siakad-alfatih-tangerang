<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dosenUsers = User::where('role', 'dosen')->get();

        $dosenData = [
            [
                'nidn' => '0301108901',
                'nama_lengkap' => 'Ahmad Fauzi',
                'gelar_depan' => 'Dr.',
                'gelar_belakang' => 'M.Kom',
                'no_telepon' => '081234567801',
                'email_dosen' => 'ahmad.fauzi@siakad.ac.id',
                'program_studi_codes' => ['PAI', 'ES'], // Assign to PAI and Ekonomi Syariah
                'mata_kuliah_codes' => ['PAI-101', 'PAI-102', 'ES-101', 'ES-102'], // Sample MK assignment
            ],
            [
                'nidn' => '0415108802',
                'nama_lengkap' => 'Siti Nurhaliza',
                'gelar_depan' => null,
                'gelar_belakang' => 'M.T',
                'no_telepon' => '081234567802',
                'email_dosen' => 'siti.nurhaliza@siakad.ac.id',
                'program_studi_codes' => ['PAI'], // Assign to PAI only
                'mata_kuliah_codes' => ['PAI-103', 'PAI-104', 'PAI-201'], // Sample MK assignment
            ],
            [
                'nidn' => '0520079001',
                'nama_lengkap' => 'Budi Santoso',
                'gelar_depan' => null,
                'gelar_belakang' => 'M.Kom',
                'no_telepon' => '081234567803',
                'email_dosen' => 'budi.santoso@siakad.ac.id',
                'program_studi_codes' => ['ES', 'PIAUD'], // Assign to ES and PIAUD
                'mata_kuliah_codes' => ['ES-201', 'ES-301', 'PIAUD-101', 'PIAUD-201'], // Sample MK assignment
            ],
        ];

        foreach ($dosenUsers as $index => $user) {
            if (isset($dosenData[$index])) {
                $data = $dosenData[$index];
                $programStudiCodes = $data['program_studi_codes'] ?? [];
                $mataKuliahCodes = $data['mata_kuliah_codes'] ?? [];
                
                unset($data['program_studi_codes']);
                unset($data['mata_kuliah_codes']);
                
                $dosen = Dosen::create(array_merge($data, [
                    'user_id' => $user->id,
                ]));
                
                // Assign dosen to program studi
                if (!empty($programStudiCodes)) {
                    $programStudiIds = \App\Models\ProgramStudi::whereIn('kode_prodi', $programStudiCodes)
                        ->pluck('id')
                        ->toArray();
                    
                    if (!empty($programStudiIds)) {
                        $dosen->programStudis()->attach($programStudiIds);
                    }
                }
                
                // Assign dosen to mata kuliah
                if (!empty($mataKuliahCodes)) {
                    $mataKuliahIds = \App\Models\MataKuliah::whereIn('kode_mk', $mataKuliahCodes)
                        ->pluck('id')
                        ->toArray();
                    
                    if (!empty($mataKuliahIds)) {
                        $dosen->mataKuliahs()->attach($mataKuliahIds);
                    }
                }
            }
        }
        
        $this->command->info('✓ Dosen seeded with program studi and mata kuliah assignments');
    }
}
