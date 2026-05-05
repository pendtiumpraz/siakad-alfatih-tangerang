<?php

/**
 * Script untuk fix nilai data yang existing
 * Problem: grade tersimpan tapi nilai_akhir & bobot NULL
 * 
 * Akan reverse engineer dari grade ke nilai_akhir & bobot
 * 
 * Usage: php fix-nilai-existing-data.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Nilai;

echo "🔧 Fixing existing nilai data...\n\n";

// Get all nilai with grade but no nilai_akhir
$nilais = Nilai::whereNotNull('grade')
    ->get();

if ($nilais->isEmpty()) {
    echo "✓ No data to fix.\n";
    exit;
}

echo "Found {$nilais->count()} records to fix...\n\n";

// Grading system mapping (reverse engineer dari grade ke nilai range).
// Skala penilaian: A 80-100 (4.00), B 70-79 (3.00), C 60-69 (2.00), D 50-59 (1.00), E 0-49 (0.00).
$gradeToNilaiBobot = [
    'A' => ['nilai' => 90, 'bobot' => 4.00],
    'B' => ['nilai' => 75, 'bobot' => 3.00],
    'C' => ['nilai' => 65, 'bobot' => 2.00],
    'D' => ['nilai' => 55, 'bobot' => 1.00],
    'E' => ['nilai' => 25, 'bobot' => 0.00],
];

$updated = 0;
$skipped = 0;

foreach ($nilais as $nilai) {
    // Skip if already has nilai_akhir
    if ($nilai->nilai_akhir !== null) {
        $skipped++;
        continue;
    }
    
    $grade = $nilai->grade;
    
    if (!isset($gradeToNilaiBobot[$grade])) {
        echo "⚠️  Unknown grade '{$grade}' for ID {$nilai->id}\n";
        $skipped++;
        continue;
    }
    
    $data = $gradeToNilaiBobot[$grade];
    
    $nilai->nilai_akhir = $data['nilai'];
    $nilai->bobot = $data['bobot'];
    $nilai->save();
    
    echo "✅ ID {$nilai->id}: Grade {$grade} → Nilai {$data['nilai']}, Bobot {$data['bobot']}\n";
    $updated++;
}

echo "\n";
echo "================================================\n";
echo "📊 SUMMARY:\n";
echo "   Total found: {$nilais->count()}\n";
echo "   Updated: {$updated}\n";
echo "   Skipped: {$skipped}\n";
echo "================================================\n";
echo "\n✨ DONE!\n";
