<?php

/**
 * Script untuk fix jenis_semester di table jadwals
 * Berdasarkan semester dari mata_kuliah
 * 
 * Semester GANJIL: 1, 3, 5, 7
 * Semester GENAP: 2, 4, 6, 8
 * 
 * Usage: php fix-jenis-semester.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Jadwal;
use App\Models\MataKuliah;

echo "🔧 Fixing jenis_semester for all jadwals...\n\n";

$jadwals = Jadwal::with('mataKuliah')->get();
$updated = 0;
$skipped = 0;

foreach ($jadwals as $jadwal) {
    if (!$jadwal->mataKuliah) {
        echo "⚠️  Jadwal ID {$jadwal->id}: Mata kuliah tidak ditemukan\n";
        $skipped++;
        continue;
    }

    $semesterNumber = $jadwal->mataKuliah->semester;
    $correctJenis = ($semesterNumber % 2 === 1) ? 'ganjil' : 'genap';
    
    if ($jadwal->jenis_semester !== $correctJenis) {
        $oldJenis = $jadwal->jenis_semester;
        $jadwal->jenis_semester = $correctJenis;
        $jadwal->save();
        
        echo "✅ Jadwal ID {$jadwal->id} ({$jadwal->mataKuliah->kode_mk}): ";
        echo "Semester {$semesterNumber} → {$oldJenis} ❌ → {$correctJenis} ✅\n";
        $updated++;
    } else {
        echo "✓  Jadwal ID {$jadwal->id} ({$jadwal->mataKuliah->kode_mk}): ";
        echo "Semester {$semesterNumber} → {$correctJenis} (sudah benar)\n";
    }
}

echo "\n";
echo "================================================\n";
echo "📊 SUMMARY:\n";
echo "   Total jadwal: " . $jadwals->count() . "\n";
echo "   Updated: {$updated}\n";
echo "   Already correct: " . ($jadwals->count() - $updated - $skipped) . "\n";
echo "   Skipped (no mata kuliah): {$skipped}\n";
echo "================================================\n";
echo "\n✨ DONE!\n";
