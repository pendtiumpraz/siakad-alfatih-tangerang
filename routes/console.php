<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-generate KHS based on khs_generate_date
Schedule::call(function () {
    $today = now()->toDateString();
    
    $semesters = \App\Models\Semester::where('khs_auto_generate', true)
        ->where('khs_generate_date', $today)
        ->whereIn('khs_status', ['draft', 'generated']) // Allow re-generation
        ->get();
    
    if ($semesters->isEmpty()) {
        \Log::info("KHS Auto-Generate: No semesters scheduled for {$today}");
        return;
    }
    
    foreach ($semesters as $semester) {
        \Log::info("KHS Auto-Generate: Starting for semester {$semester->id} ({$semester->tahun_akademik})");
        
        try {
            Artisan::call('khs:generate', [
                'semester_id' => $semester->id,
                '--force' => true,
            ]);
            
            $output = Artisan::output();
            \Log::info("KHS Auto-Generate: Completed for semester {$semester->id}", [
                'output' => $output
            ]);
            
        } catch (\Exception $e) {
            \Log::error("KHS Auto-Generate: Failed for semester {$semester->id}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
})
->dailyAt('02:00')
->name('auto-generate-khs')
->withoutOverlapping()
->onOneServer();
