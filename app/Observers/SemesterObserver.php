<?php

namespace App\Observers;

use App\Models\Semester;
use App\Services\SppAutoGenerateService;
use Illuminate\Support\Facades\Log;

class SemesterObserver
{
    /**
     * Handle the Semester "updating" event.
     * 
     * This runs BEFORE the update, so we can capture the old is_active state
     */
    public function updating(Semester $semester): void
    {
        // Store the old is_active value before update
        if ($semester->isDirty('is_active')) {
            $semester->old_is_active = $semester->getOriginal('is_active');
        }
    }

    /**
     * Handle the Semester "updated" event.
     * 
     * Detect when is_active changes from FALSE -> TRUE
     * Then auto-generate SPP for all active mahasiswa
     */
    public function updated(Semester $semester): void
    {
        // Check if is_active changed to TRUE
        if ($semester->isDirty('is_active') && $semester->is_active === true) {
            
            $oldIsActive = $semester->old_is_active ?? false;
            
            // Only proceed if changed from FALSE -> TRUE
            if (!$oldIsActive) {
                
                Log::info('Semester activated, checking SPP auto-generation', [
                    'semester_id' => $semester->id,
                    'semester' => $semester->nama_semester,
                ]);

                // Get previous active semester (to check valid progression)
                $previousSemester = Semester::where('id', '!=', $semester->id)
                    ->orderBy('tanggal_mulai', 'desc')
                    ->first();

                // Auto-generate SPP
                $service = new SppAutoGenerateService();
                $result = $service->generateSppForSemester($semester, $previousSemester);

                // Log result
                if ($result['success']) {
                    Log::info('SPP Auto-generation successful', $result);
                } else {
                    Log::warning('SPP Auto-generation failed or skipped', $result);
                }
            }
        }
    }
}
