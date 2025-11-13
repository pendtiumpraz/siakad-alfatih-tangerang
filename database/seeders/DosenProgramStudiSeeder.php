<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\ProgramStudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DosenProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder assigns existing dosen to program studi
     */
    public function run(): void
    {
        $this->command->info('Assigning dosen to program studi...');
        
        // Get all program studi
        $programStudis = ProgramStudi::all()->keyBy('kode_prodi');
        
        if ($programStudis->isEmpty()) {
            $this->command->warn('No program studi found. Run ProgramStudiSeeder first.');
            return;
        }
        
        // Get all dosen
        $dosens = Dosen::all();
        
        if ($dosens->isEmpty()) {
            $this->command->warn('No dosen found. Run DosenSeeder first.');
            return;
        }
        
        // Assignment logic based on NIDN or other criteria
        // You can customize this based on your needs
        $assignments = [
            // Example: Assign based on NIDN pattern or manual mapping
            '0301108901' => ['PAI', 'ES'],    // Ahmad Fauzi -> PAI & Ekonomi Syariah
            '0415108802' => ['PAI'],          // Siti Nurhaliza -> PAI
            '0520079001' => ['ES', 'PIAUD'],  // Budi Santoso -> ES & PIAUD
        ];
        
        $assigned = 0;
        $skipped = 0;
        
        foreach ($dosens as $dosen) {
            // Check if dosen already has program studi assigned
            if ($dosen->programStudis()->count() > 0) {
                $this->command->info("Dosen {$dosen->nama_lengkap} already has program studi assigned. Skipping...");
                $skipped++;
                continue;
            }
            
            // Get program studi codes for this dosen
            $prodiCodes = $assignments[$dosen->nidn] ?? [];
            
            // If no specific assignment, assign to all program studi (default behavior)
            if (empty($prodiCodes)) {
                $prodiCodes = $programStudis->keys()->toArray();
                $this->command->info("No specific assignment for {$dosen->nama_lengkap}. Assigning to all program studi.");
            }
            
            // Get program studi IDs
            $prodiIds = [];
            foreach ($prodiCodes as $code) {
                if ($programStudis->has($code)) {
                    $prodiIds[] = $programStudis[$code]->id;
                }
            }
            
            // Attach program studi to dosen
            if (!empty($prodiIds)) {
                $dosen->programStudis()->attach($prodiIds);
                $this->command->info("Assigned {$dosen->nama_lengkap} to: " . implode(', ', $prodiCodes));
                $assigned++;
            } else {
                $this->command->warn("Could not find program studi for {$dosen->nama_lengkap}");
            }
        }
        
        $this->command->info("Assignment completed! Assigned: {$assigned}, Skipped: {$skipped}");
    }
}
