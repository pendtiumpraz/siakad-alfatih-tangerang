<?php

/**
 * Test ALL SPP Generation Scenarios
 * Verify logic handles all edge cases
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║         TEST ALL SPP GENERATION SCENARIOS                    ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$service = new \App\Services\SppAutoGenerateService();

// Clean up first
echo "🧹 Cleaning up test data...\n";
\App\Models\Pembayaran::where('jenis_pembayaran', 'spp')
    ->where('semester_id', 6)
    ->delete();
echo "✓ Cleaned\n\n";

echo str_repeat("═", 60) . "\n";
echo "SCENARIO 1: Valid Progression (Ganjil → Genap same year)\n";
echo str_repeat("═", 60) . "\n";

$ganjil2024 = \App\Models\Semester::find(1); // Ganjil 2024/2025
$genap2024 = \App\Models\Semester::find(6);  // Genap 2024/2025

echo "From: {$ganjil2024->nama_semester} {$ganjil2024->tahun_akademik}\n";
echo "To:   {$genap2024->nama_semester} {$genap2024->tahun_akademik}\n\n";

$result1 = $service->generateSppForSemester($genap2024, $ganjil2024);

if ($result1['success'] && $result1['count'] > 0) {
    echo "✅ PASS: Generated {$result1['count']} records\n";
} else {
    echo "❌ FAIL: {$result1['message']}\n";
}

$countAfter1 = \App\Models\Pembayaran::where('jenis_pembayaran', 'spp')
    ->where('semester_id', 6)
    ->count();
echo "Database: {$countAfter1} records\n\n";

echo str_repeat("═", 60) . "\n";
echo "SCENARIO 2: Invalid - Backwards (Genap → Ganjil previous year)\n";
echo str_repeat("═", 60) . "\n";

$ganjil2023 = \App\Models\Semester::find(5); // Ganjil 2023/2024

echo "From: {$genap2024->nama_semester} {$genap2024->tahun_akademik}\n";
echo "To:   {$ganjil2023->nama_semester} {$ganjil2023->tahun_akademik}\n\n";

$result2 = $service->generateSppForSemester($ganjil2023, $genap2024);

if (!$result2['success'] && $result2['count'] == 0) {
    echo "✅ PASS: Correctly rejected (backwards)\n";
    echo "Message: {$result2['message']}\n";
} else {
    echo "❌ FAIL: Should NOT generate for backwards progression\n";
}

$countAfter2 = \App\Models\Pembayaran::where('jenis_pembayaran', 'spp')
    ->where('semester_id', 5)
    ->count();
echo "Database: {$countAfter2} records (should be 0)\n\n";

echo str_repeat("═", 60) . "\n";
echo "SCENARIO 3: Invalid - Skip Semester (Ganjil 2023 → Ganjil 2024)\n";
echo str_repeat("═", 60) . "\n";

echo "From: {$ganjil2023->nama_semester} {$ganjil2023->tahun_akademik}\n";
echo "To:   {$ganjil2024->nama_semester} {$ganjil2024->tahun_akademik}\n\n";

$result3 = $service->generateSppForSemester($ganjil2024, $ganjil2023);

if (!$result3['success'] && $result3['count'] == 0) {
    echo "✅ PASS: Correctly rejected (skip semester)\n";
    echo "Message: {$result3['message']}\n";
} else {
    echo "❌ FAIL: Should NOT generate when skipping semester\n";
}

$countAfter3 = \App\Models\Pembayaran::where('jenis_pembayaran', 'spp')
    ->where('semester_id', 1)
    ->count();
echo "Database: {$countAfter3} records for semester 1 (should be 0 or existing)\n\n";

echo str_repeat("═", 60) . "\n";
echo "SCENARIO 4: Duplicate Prevention (Genap 2024/2025 again)\n";
echo str_repeat("═", 60) . "\n";

echo "From: {$ganjil2024->nama_semester} {$ganjil2024->tahun_akademik}\n";
echo "To:   {$genap2024->nama_semester} {$genap2024->tahun_akademik}\n";
echo "(Already generated in Scenario 1)\n\n";

$countBefore4 = \App\Models\Pembayaran::where('jenis_pembayaran', 'spp')
    ->where('semester_id', 6)
    ->count();

$result4 = $service->generateSppForSemester($genap2024, $ganjil2024);

$countAfter4 = \App\Models\Pembayaran::where('jenis_pembayaran', 'spp')
    ->where('semester_id', 6)
    ->count();

if ($result4['success'] && $result4['count'] == 0 && $countBefore4 == $countAfter4) {
    echo "✅ PASS: Correctly skipped (records already exist)\n";
    echo "Message: {$result4['message']}\n";
    echo "Before: {$countBefore4} records\n";
    echo "After:  {$countAfter4} records (no change)\n";
} else {
    echo "❌ FAIL: Should skip existing records\n";
    echo "Generated: {$result4['count']} new records\n";
    echo "Before: {$countBefore4}, After: {$countAfter4}\n";
}

echo "\n";
echo str_repeat("═", 60) . "\n";
echo "SCENARIO 5: Valid Next Progression (Genap 2024 → Ganjil 2025)\n";
echo str_repeat("═", 60) . "\n";

$ganjil2025 = \App\Models\Semester::find(7); // Ganjil 2025/2026

// Clean first
\App\Models\Pembayaran::where('jenis_pembayaran', 'spp')
    ->where('semester_id', 7)
    ->delete();

echo "From: {$genap2024->nama_semester} {$genap2024->tahun_akademik}\n";
echo "To:   {$ganjil2025->nama_semester} {$ganjil2025->tahun_akademik}\n\n";

$result5 = $service->generateSppForSemester($ganjil2025, $genap2024);

if ($result5['success'] && $result5['count'] > 0) {
    echo "✅ PASS: Generated {$result5['count']} records\n";
} else {
    echo "❌ FAIL: {$result5['message']}\n";
}

$countAfter5 = \App\Models\Pembayaran::where('jenis_pembayaran', 'spp')
    ->where('semester_id', 7)
    ->count();
echo "Database: {$countAfter5} records\n\n";

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                    SUMMARY                                   ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$tests = [
    "Scenario 1 (Valid: Ganjil→Genap)" => ($result1['success'] && $result1['count'] > 0),
    "Scenario 2 (Reject: Backwards)" => (!$result2['success'] && $result2['count'] == 0),
    "Scenario 3 (Reject: Skip)" => (!$result3['success'] && $result3['count'] == 0),
    "Scenario 4 (Skip: Duplicate)" => ($result4['success'] && $result4['count'] == 0),
    "Scenario 5 (Valid: Genap→Ganjil)" => ($result5['success'] && $result5['count'] > 0),
];

$passed = 0;
$total = count($tests);

foreach ($tests as $name => $result) {
    $status = $result ? "✅ PASS" : "❌ FAIL";
    echo "{$status} - {$name}\n";
    if ($result) $passed++;
}

echo "\n";
echo str_repeat("─", 60) . "\n";
echo "RESULT: {$passed}/{$total} tests passed\n";

if ($passed == $total) {
    echo "\n🎉 ALL TESTS PASSED! Logic is correct!\n";
} else {
    echo "\n⚠️  Some tests failed. Review logic.\n";
}

echo "\n";
