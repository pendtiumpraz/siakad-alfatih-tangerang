<?php

/**
 * Script untuk regenerate KHS semua mahasiswa
 * Karena data nilai sudah di-fix, KHS perlu di-recalculate
 * 
 * Usage: php regenerate-khs.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Mahasiswa;
use App\Models\Semester;
use App\Models\Khs;
use App\Models\Nilai;

echo "🔧 Regenerating KHS for all mahasiswa...\n\n";

// Get all mahasiswa with nilai
$mahasiswas = Mahasiswa::whereHas('nilais')->get();

if ($mahasiswas->isEmpty()) {
    echo "❌ No mahasiswa with nilai found!\n";
    exit;
}

echo "Found {$mahasiswas->count()} mahasiswa with nilai\n\n";

$regenerated = 0;
$errors = 0;

foreach ($mahasiswas as $mhs) {
    echo "Processing: {$mhs->nim} - {$mhs->nama_lengkap}\n";
    
    // Get all semesters where this mahasiswa has nilai
    $semesterIds = $mhs->nilais()->pluck('semester_id')->unique();
    
    foreach ($semesterIds as $semesterId) {
        try {
            $semester = Semester::find($semesterId);
            if (!$semester) continue;
            
            // Calculate IP for this semester
            $nilais = Nilai::where('mahasiswa_id', $mhs->id)
                ->where('semester_id', $semesterId)
                ->with('mataKuliah')
                ->get();
            
            if ($nilais->isEmpty()) continue;
            
            $totalSks = 0;
            $totalBobotXSks = 0;
            
            foreach ($nilais as $nilai) {
                $sks = $nilai->mataKuliah->sks ?? 0;
                $bobot = $nilai->bobot ?? 0;
                
                $totalSks += $sks;
                $totalBobotXSks += ($bobot * $sks);
            }
            
            $ip = $totalSks > 0 ? $totalBobotXSks / $totalSks : 0;
            
            // Calculate IPK (kumulatif up to this semester)
            $allNilais = Nilai::where('mahasiswa_id', $mhs->id)
                ->where('semester_id', '<=', $semesterId)
                ->with('mataKuliah')
                ->get();
            
            $totalSksKumulatif = 0;
            $totalBobotXSksKumulatif = 0;
            
            foreach ($allNilais as $nilai) {
                $sks = $nilai->mataKuliah->sks ?? 0;
                $bobot = $nilai->bobot ?? 0;
                
                $totalSksKumulatif += $sks;
                $totalBobotXSksKumulatif += ($bobot * $sks);
            }
            
            $ipk = $totalSksKumulatif > 0 ? $totalBobotXSksKumulatif / $totalSksKumulatif : 0;
            
            // Update or create KHS
            Khs::updateOrCreate(
                [
                    'mahasiswa_id' => $mhs->id,
                    'semester_id' => $semesterId,
                ],
                [
                    'total_sks_semester' => $totalSks,
                    'total_sks_kumulatif' => $totalSksKumulatif,
                    'ip' => round($ip, 2),
                    'ipk' => round($ipk, 2),
                ]
            );
            
            echo "  ✅ Semester {$semester->nama_semester}: IP = " . round($ip, 2) . ", IPK = " . round($ipk, 2) . "\n";
            $regenerated++;
            
        } catch (\Exception $e) {
            echo "  ❌ Error: " . $e->getMessage() . "\n";
            $errors++;
        }
    }
    
    echo "\n";
}

echo "================================================\n";
echo "📊 SUMMARY:\n";
echo "   Mahasiswa processed: {$mahasiswas->count()}\n";
echo "   KHS regenerated: {$regenerated}\n";
echo "   Errors: {$errors}\n";
echo "================================================\n";
echo "\n✨ DONE!\n";
