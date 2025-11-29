<?php

/**
 * Activate Semester and Test SPP Auto-Generate
 * Run: php activate-semester.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║         ACTIVATE SEMESTER & TEST AUTO-GENERATION             ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Get current active
$currentActive = \App\Models\Semester::where('is_active', true)->first();
echo "📌 Current active: {$currentActive->nama_semester} {$currentActive->tahun_akademik} (ID: {$currentActive->id})\n\n";

// Get next semester
$nextSemester = \App\Models\Semester::find(6);
echo "🎯 Target: {$nextSemester->nama_semester} {$nextSemester->tahun_akademik} (ID: {$nextSemester->id})\n";
echo "   Status before: " . ($nextSemester->is_active ? 'ACTIVE' : 'INACTIVE') . "\n\n";

// Count mahasiswa
$mahasiswaCount = \App\Models\Mahasiswa::where('status', 'aktif')->count();
echo "👥 Active mahasiswa: {$mahasiswaCount}\n\n";

// Check existing SPP for semester 6
$existingBefore = \App\Models\Pembayaran::where('jenis_pembayaran', 'spp')
    ->where('semester_id', 6)
    ->count();
echo "💰 SPP records for semester 6 BEFORE: {$existingBefore}\n\n";

echo str_repeat("─", 60) . "\n";
echo "🚀 ACTIVATING SEMESTER 6...\n";
echo str_repeat("─", 60) . "\n\n";

// Deactivate current
$currentActive->is_active = false;
$currentActive->save();
echo "✓ Deactivated semester {$currentActive->id}\n";

// Activate semester 6
$nextSemester->is_active = true;
$nextSemester->save();
echo "✓ Activated semester 6\n\n";

// Wait a bit for observer
sleep(2);

// Check results
echo str_repeat("─", 60) . "\n";
echo "📊 CHECKING RESULTS...\n";
echo str_repeat("─", 60) . "\n\n";

$existingAfter = \App\Models\Pembayaran::where('jenis_pembayaran', 'spp')
    ->where('semester_id', 6)
    ->count();

$generated = $existingAfter - $existingBefore;

echo "💰 SPP records for semester 6 AFTER: {$existingAfter}\n";
echo "📈 New records generated: {$generated}\n\n";

if ($generated == $mahasiswaCount) {
    echo "✅ SUCCESS! All {$mahasiswaCount} mahasiswa got SPP records!\n\n";
} elseif ($generated > 0) {
    echo "⚠️  PARTIAL SUCCESS: Only {$generated} out of {$mahasiswaCount} records created\n\n";
} else {
    echo "❌ FAILED: No records generated\n";
    echo "   Check logs: tail -f storage/logs/laravel.log\n\n";
}

// Show sample records
if ($generated > 0) {
    echo "📋 Sample SPP Records:\n";
    echo str_repeat("─", 60) . "\n";
    
    $samples = \App\Models\Pembayaran::with('mahasiswa')
        ->where('jenis_pembayaran', 'spp')
        ->where('semester_id', 6)
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    foreach ($samples as $p) {
        echo sprintf(
            "  - %s: Rp %s, Due: %s, Status: %s\n",
            $p->mahasiswa->nama_lengkap,
            number_format($p->jumlah, 0, ',', '.'),
            $p->tanggal_jatuh_tempo->format('d M Y'),
            $p->status
        );
    }
    
    echo "\n";
}

// Show breakdown
$statusBreakdown = \App\Models\Pembayaran::where('jenis_pembayaran', 'spp')
    ->where('semester_id', 6)
    ->selectRaw('status, COUNT(*) as total')
    ->groupBy('status')
    ->get();

echo "📊 Status Breakdown:\n";
foreach ($statusBreakdown as $stat) {
    echo "   {$stat->status}: {$stat->total}\n";
}

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                    TEST COMPLETE                             ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

echo "Next steps:\n";
echo "1. Check operator/pembayaran - should see {$generated} new SPP records\n";
echo "2. Login as mahasiswa - should see SPP in pembayaran page\n";
echo "3. Try access KHS/KRS - should be blocked if unpaid\n";
echo "4. Operator approve payment - change status to 'lunas'\n";
echo "5. Mahasiswa try KHS/KRS again - should allow access\n\n";
