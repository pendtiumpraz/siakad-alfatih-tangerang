<?php

/**
 * Test SPP Auto-Generate System
 * Run: php test-spp-auto-generate.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║       TEST SPP AUTO-GENERATE SYSTEM                          ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// 1. Check available semesters
echo "📋 Step 1: Checking Available Semesters\n";
echo str_repeat("─", 60) . "\n";

$semesters = \App\Models\Semester::orderBy('tanggal_mulai', 'desc')->get();

echo sprintf("%-5s %-30s %-15s %-10s\n", "ID", "Nama Semester", "Tahun Akademik", "Active");
echo str_repeat("─", 60) . "\n";

foreach ($semesters as $sem) {
    echo sprintf(
        "%-5s %-30s %-15s %-10s\n",
        $sem->id,
        $sem->nama_semester,
        $sem->tahun_akademik,
        $sem->is_active ? '✓ YES' : '✗ NO'
    );
}

$activeSemester = \App\Models\Semester::where('is_active', true)->first();
$nextSemester = null;

if ($activeSemester) {
    echo "\n✓ Current active: {$activeSemester->nama_semester} {$activeSemester->tahun_akademik} (ID: {$activeSemester->id})\n";
    
    // Find next semester
    $currentYear = (int) substr($activeSemester->tahun_akademik, 0, 4);
    $isGanjil = strpos(strtolower($activeSemester->nama_semester), 'ganjil') !== false;
    
    if ($isGanjil) {
        // Next is Genap same year
        $nextSemester = \App\Models\Semester::where('tahun_akademik', $activeSemester->tahun_akademik)
            ->whereRaw("LOWER(nama_semester) LIKE '%genap%'")
            ->first();
    } else {
        // Next is Ganjil next year
        $nextYear = $currentYear + 1;
        $nextYearAcademic = $nextYear . '/' . ($nextYear + 1);
        $nextSemester = \App\Models\Semester::where('tahun_akademik', $nextYearAcademic)
            ->whereRaw("LOWER(nama_semester) LIKE '%ganjil%'")
            ->first();
    }
    
    if ($nextSemester) {
        echo "→ Next valid semester: {$nextSemester->nama_semester} {$nextSemester->tahun_akademik} (ID: {$nextSemester->id})\n";
    } else {
        echo "✗ No next semester found\n";
    }
}

echo "\n";

// 2. Check active mahasiswa
echo "👨‍🎓 Step 2: Checking Active Mahasiswa\n";
echo str_repeat("─", 60) . "\n";

$mahasiswaCount = \App\Models\Mahasiswa::where('status', 'aktif')->count();
echo "Total active mahasiswa: {$mahasiswaCount}\n";

if ($mahasiswaCount > 0) {
    $sample = \App\Models\Mahasiswa::where('status', 'aktif')->limit(3)->get();
    echo "\nSample mahasiswa:\n";
    foreach ($sample as $mhs) {
        echo "  - {$mhs->nama_lengkap} (NIM: {$mhs->nim})\n";
    }
}

echo "\n";

// 3. Check existing SPP pembayaran
if ($activeSemester) {
    echo "💰 Step 3: Checking Existing SPP Pembayaran\n";
    echo str_repeat("─", 60) . "\n";
    
    $sppCount = \App\Models\Pembayaran::where('jenis_pembayaran', 'spp')
        ->where('semester_id', $activeSemester->id)
        ->count();
    
    echo "SPP records for active semester: {$sppCount}\n";
    
    if ($sppCount > 0) {
        $statusBreakdown = \App\Models\Pembayaran::where('jenis_pembayaran', 'spp')
            ->where('semester_id', $activeSemester->id)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();
        
        echo "\nStatus breakdown:\n";
        foreach ($statusBreakdown as $stat) {
            echo "  - {$stat->status}: {$stat->total}\n";
        }
    }
    
    echo "\n";
}

// 4. Ask user to test
if ($nextSemester && !$nextSemester->is_active) {
    echo "🧪 Step 4: Ready to Test Auto-Generation\n";
    echo str_repeat("─", 60) . "\n";
    echo "Next semester found: {$nextSemester->nama_semester} {$nextSemester->tahun_akademik}\n";
    echo "\nTo test auto-generation:\n";
    echo "  1. Run: php artisan tinker\n";
    echo "  2. Execute:\n";
    echo "     \$sem = \\App\\Models\\Semester::find({$nextSemester->id});\n";
    echo "     \$sem->is_active = true;\n";
    echo "     \$sem->save();\n";
    echo "  3. Check logs: tail -f storage/logs/laravel.log\n";
    echo "  4. Verify: Pembayaran::where('jenis_pembayaran', 'spp')->where('semester_id', {$nextSemester->id})->count();\n";
    echo "\nExpected result: {$mahasiswaCount} new SPP records created\n";
} else {
    echo "⚠️  Step 4: Cannot Test\n";
    echo str_repeat("─", 60) . "\n";
    
    if (!$nextSemester) {
        echo "✗ No next semester found in database\n";
        echo "  Please create next semester first\n";
    } elseif ($nextSemester->is_active) {
        echo "✗ Next semester is already active\n";
        echo "  Observer only triggers on FALSE → TRUE change\n";
    }
}

echo "\n";

// 5. Manual test option
echo "🔧 Step 5: Manual Test Service\n";
echo str_repeat("─", 60) . "\n";
echo "To manually test the service:\n\n";
echo "php artisan tinker\n";
$nextId = $nextSemester ? $nextSemester->id : 'SEMESTER_ID';
$activeId = $activeSemester ? $activeSemester->id : 'PREVIOUS_ID';

echo "\$service = new \\App\\Services\\SppAutoGenerateService();\n";
echo "\$semester = \\App\\Models\\Semester::find({$nextId});\n";
echo "\$previousSemester = \\App\\Models\\Semester::find({$activeId});\n";
echo "\$result = \$service->generateSppForSemester(\$semester, \$previousSemester);\n";
echo "print_r(\$result);\n";

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                    TEST COMPLETE                             ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";
