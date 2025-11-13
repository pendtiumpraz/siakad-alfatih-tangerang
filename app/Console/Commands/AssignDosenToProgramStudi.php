<?php

namespace App\Console\Commands;

use App\Models\Dosen;
use App\Models\ProgramStudi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AssignDosenToProgramStudi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dosen:assign-prodi 
                            {--all : Assign all dosen to all program studi}
                            {--force : Force reassign even if already assigned}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign existing dosen to program studi (for data migration)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if migration has been run
        if (!DB::getSchemaBuilder()->hasTable('dosen_program_studi')) {
            $this->error('Table dosen_program_studi does not exist. Please run: php artisan migrate');
            return 1;
        }

        $this->info('🔄 Starting dosen to program studi assignment...');
        $this->newLine();

        // Get all dosen
        $dosens = Dosen::all();
        
        if ($dosens->isEmpty()) {
            $this->warn('No dosen found in database.');
            return 0;
        }

        // Get all program studi
        $programStudis = ProgramStudi::all();
        
        if ($programStudis->isEmpty()) {
            $this->error('No program studi found. Please seed program studi first.');
            return 1;
        }

        $this->info("Found {$dosens->count()} dosen and {$programStudis->count()} program studi");
        $this->newLine();

        $assigned = 0;
        $skipped = 0;
        $updated = 0;

        $bar = $this->output->createProgressBar($dosens->count());
        $bar->start();

        foreach ($dosens as $dosen) {
            try {
                $currentProdi = $dosen->programStudis()->count();
                
                if ($currentProdi > 0 && !$this->option('force')) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                if ($currentProdi > 0 && $this->option('force')) {
                    // Detach all first
                    $dosen->programStudis()->detach();
                }

                // Assign to all program studi
                if ($this->option('all')) {
                    $prodiIds = $programStudis->pluck('id')->toArray();
                    $dosen->programStudis()->attach($prodiIds);
                    
                    if ($currentProdi > 0) {
                        $updated++;
                    } else {
                        $assigned++;
                    }
                } else {
                    // Default: assign to first program studi only
                    $dosen->programStudis()->attach($programStudis->first()->id);
                    
                    if ($currentProdi > 0) {
                        $updated++;
                    } else {
                        $assigned++;
                    }
                }
            } catch (\Throwable $e) {
                $this->newLine();
                $this->error("Failed to assign dosen {$dosen->nama_lengkap}: {$e->getMessage()}");
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info('✅ Assignment completed!');
        $this->table(
            ['Status', 'Count'],
            [
                ['New Assignments', $assigned],
                ['Updated', $updated],
                ['Skipped (already assigned)', $skipped],
                ['Total Processed', $dosens->count()],
            ]
        );

        if ($assigned > 0 || $updated > 0) {
            $this->newLine();
            $this->info('💡 Tip: You can now edit dosen in admin panel without errors!');
        }

        return 0;
    }
}
