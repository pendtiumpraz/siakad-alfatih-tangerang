<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Nilai;
use Illuminate\Support\Facades\DB;

echo "🔍 Checking actual nilai data in database...\n\n";

// Get last 10 nilai records
$nilais = Nilai::with(['mahasiswa', 'mataKuliah'])
    ->orderBy('id', 'desc')
    ->limit(10)
    ->get();

if ($nilais->isEmpty()) {
    echo "❌ No nilai data found!\n";
    exit;
}

echo "📊 Last 10 Nilai Records:\n";
echo str_repeat('=', 120) . "\n";
printf(
    "%-5s | %-15s | %-30s | %-10s | %-10s | %-6s | %-6s | %-8s\n",
    'ID', 'NIM', 'Mata Kuliah', 'N.Tugas', 'N.UTS', 'N.UAS', 'N.Akhir', 'Grade', 'Bobot'
);
echo str_repeat('=', 120) . "\n";

foreach ($nilais as $nilai) {
    printf(
        "%-5s | %-15s | %-30s | %-10s | %-6s | %-6s | %-8s | %-6s | %-6s\n",
        $nilai->id,
        $nilai->mahasiswa->nim ?? 'N/A',
        substr($nilai->mataKuliah->kode_mk ?? 'N/A', 0, 30),
        $nilai->nilai_tugas ?? '-',
        $nilai->nilai_uts ?? '-',
        $nilai->nilai_uas ?? '-',
        $nilai->nilai_akhir ?? '❌ NULL',
        $nilai->grade ?? '❌ NULL',
        $nilai->bobot ?? '❌ NULL'
    );
}

echo str_repeat('=', 120) . "\n\n";

// Check specific problematic records
echo "🔎 Checking records with grade but no nilai_akhir:\n";
$problematic = Nilai::whereNotNull('grade')
    ->whereNull('nilai_akhir')
    ->count();

echo "Found: {$problematic} records with grade but NULL nilai_akhir\n\n";

// Check if bobot exists
$withBobot = Nilai::whereNotNull('bobot')->count();
$total = Nilai::count();
echo "📊 Bobot field status:\n";
echo "   Total records: {$total}\n";
echo "   With bobot: {$withBobot}\n";
echo "   Without bobot: " . ($total - $withBobot) . "\n\n";

// Raw query to check actual columns
echo "🔧 Checking table structure:\n";
$columns = DB::select("SHOW COLUMNS FROM nilais WHERE Field IN ('nilai_akhir', 'grade', 'bobot')");
foreach ($columns as $col) {
    echo sprintf("   %-15s | %-20s | %s\n", $col->Field, $col->Type, $col->Null === 'YES' ? 'NULLABLE' : 'NOT NULL');
}
