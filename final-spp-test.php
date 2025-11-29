<?php

/**
 * Final SPP Auto-Generate Test
 * Run: php final-spp-test.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║           FINAL SPP AUTO-GENERATE TEST                       ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// STEP 1: Reset - Make semester 1 active
echo "🔄 Step 1: Resetting to semester 1...\n";
\App\Models\Semester::query()->update(['is_active' => false]);
$sem1 = \App\Models\Semester::find(1);
$sem1->is_active = true;
$sem1->save();
echo "✓ Semester 1 (Ganjil 2024/2025) is now active\n\n";

// STEP 2: Check mahasiswa count
$mahasiswaCount = \App\Models\Mahasiswa::where('status', 'aktif')->count();
echo "👥 Active mahasiswa: {$mahasiswaCount}\n\n";

// STEP 3: Delete any existing SPP for semester 6 (for clean test)
\App\Models\Pembayaran::where('jenis_pembayaran', 'spp')
    ->where('semester_id', 6)
    ->delete();
echo "🗑️  Cleaned existing SPP records for semester 6\n\n";

// STEP 4: Activate semester 6
echo str_repeat("─", 60) . "\n";
echo "🚀 ACTIVATING SEMESTER 6 (Genap 2024/2025)...\n";
echo str_repeat("─", 60) . "\n\n";

$sem6 = \App\Models\Semester::find(6);
$sem6->is_active = true;
$sem6->save();

echo "✓ Semester 6 activated\n\n";

// Wait a bit
sleep(2);

// STEP 5: Check results
echo str_repeat("─", 60) . "\n";
echo "📊 CHECKING RESULTS...\n";
echo str_repeat("─", 60) . "\n\n";

$generated = \App\Models\Pembayaran::where('jenis_pembayaran', 'spp')
    ->where('semester_id', 6)
    ->count();

echo "💰 SPP records created: {$generated}\n";
echo "📈 Expected: {$mahasiswaCount}\n\n";

if ($generated == $mahasiswaCount) {
    echo "✅ SUCCESS! All {$mahasiswaCount} mahasiswa got SPP records!\n\n";
    
    // Show sample
    $samples = \App\Models\Pembayaran::with('mahasiswa')
        ->where('jenis_pembayaran', 'spp')
        ->where('semester_id', 6)
        ->limit(5)
        ->get();
    
    echo "📋 Sample Records:\n";
    echo str_repeat("─", 60) . "\n";
    foreach ($samples as $p) {
        echo sprintf(
            "  %s\n  Rp %s | Due: %s | Status: %s\n\n",
            $p->mahasiswa->nama_lengkap,
            number_format($p->jumlah, 0, ',', '.'),
            $p->tanggal_jatuh_tempo->format('d M Y'),
            $p->status
        );
    }
    
    echo "✅ TEST PASSED!\n\n";
    
} elseif ($generated > 0) {
    echo "⚠️  PARTIAL: Only {$generated}/{$mahasiswaCount} records created\n\n";
} else {
    echo "❌ FAILED: No records generated\n";
    echo "   Checking why...\n\n";
    
    // Debug: Check if observer is registered
    echo "   Checking observer registration...\n";
    $observers = get_class_methods(\App\Observers\SemesterObserver::class);
    echo "   Observer methods: " . implode(', ', $observers) . "\n\n";
}

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                     TEST COMPLETE                            ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";
