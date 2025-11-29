<?php

namespace App\Observers;

use App\Models\Semester;
use App\Services\SppAutoGenerateService;
use Illuminate\Support\Facades\Log;

class SemesterObserver
{
    /**
     * Handle the Semester "updated" event.
     * 
     * Detect when is_active changes from FALSE -> TRUE
     * Then auto-generate SPP for all active mahasiswa
     */
    public function updated(Semester $semester): void
    {
        // Get changes that were made
        $changes = $semester->getChanges();
        
        // Check if is_active was changed AND new value is TRUE
        if (isset($changes['is_active']) && $changes['is_active'] == 1) {
            
            Log::info('Semester activated, checking SPP auto-generation', [
                'semester_id' => $semester->id,
                'semester' => $semester->nama_semester,
                'changes' => $changes,
            ]);

            // Get previous active semester (to check valid progression)
            $previousSemester = Semester::where('id', '!=', $semester->id)
                ->where('is_active', false) // Get previously active (now inactive)
                ->orderBy('updated_at', 'desc')
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
