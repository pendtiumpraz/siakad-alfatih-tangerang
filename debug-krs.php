<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ProgramStudi;
use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\Semester;
use App\Models\Jadwal;
use App\Models\Mahasiswa;

echo "=== DEBUG KRS SYSTEM ===\n\n";

// 1. Check Program Studi
echo "1. PROGRAM STUDI:\n";
$prodi = ProgramStudi::where('nama_prodi', 'like', '%Pendidikan Agama Islam%')->first();
if ($prodi) {
    echo "   ✓ Found: {$prodi->nama_prodi} (ID: {$prodi->id})\n";
} else {
    echo "   ✗ NOT FOUND!\n";
    exit;
}

// 2. Check Kurikulum
echo "\n2. KURIKULUM AKTIF:\n";
$kurikulum = Kurikulum::where('program_studi_id', $prodi->id)
    ->where('is_active', true)
    ->first();
if ($kurikulum) {
    echo "   ✓ Found: {$kurikulum->nama_kurikulum} (ID: {$kurikulum->id})\n";
} else {
    echo "   ✗ NO ACTIVE KURIKULUM!\n";
    echo "   All kurikulum for this prodi:\n";
    $allKurikulum = Kurikulum::where('program_studi_id', $prodi->id)->get();
    foreach ($allKurikulum as $k) {
        echo "     - {$k->nama_kurikulum} (is_active: {$k->is_active})\n";
    }
    exit;
}

// 3. Check Mata Kuliah
echo "\n3. MATA KULIAH WAJIB:\n";
$mataKuliahs = MataKuliah::where('kurikulum_id', $kurikulum->id)
    ->where('jenis', 'wajib')
    ->get();
echo "   Total: " . $mataKuliahs->count() . " mata kuliah\n";
if ($mataKuliahs->count() > 0) {
    echo "   Breakdown per semester:\n";
    for ($sem = 1; $sem <= 8; $sem++) {
        $count = $mataKuliahs->where('semester', $sem)->count();
        if ($count > 0) {
            echo "     - Semester {$sem}: {$count} MK\n";
        }
    }
} else {
    echo "   ✗ NO MATA KULIAH WAJIB FOUND!\n";
}

// 4. Check Active Semester
echo "\n4. SEMESTER AKTIF:\n";
$semester = Semester::where('is_active', true)->first();
if ($semester) {
    echo "   ✓ Found: {$semester->nama_semester} {$semester->tahun_akademik} (ID: {$semester->id})\n";
} else {
    echo "   ✗ NO ACTIVE SEMESTER!\n";
    exit;
}

// 5. Check Jadwal
echo "\n5. JADWAL:\n";
$jadwalCount = Jadwal::where('semester_id', $semester->id)
    ->whereIn('mata_kuliah_id', $mataKuliahs->pluck('id'))
    ->count();
echo "   Total jadwal untuk MK wajib prodi ini: {$jadwalCount}\n";

if ($jadwalCount == 0) {
    echo "   ✗ NO JADWAL FOUND FOR MATA KULIAH WAJIB!\n";
    echo "\n   Detail:\n";
    $mkWithJadwal = [];
    $mkWithoutJadwal = [];
    
    foreach ($mataKuliahs as $mk) {
        $hasJadwal = Jadwal::where('semester_id', $semester->id)
            ->where('mata_kuliah_id', $mk->id)
            ->exists();
        
        if ($hasJadwal) {
            $mkWithJadwal[] = $mk->nama_mk;
        } else {
            $mkWithoutJadwal[] = "{$mk->nama_mk} (Sem {$mk->semester})";
        }
    }
    
    if (count($mkWithJadwal) > 0) {
        echo "\n   ✓ MK yang ADA jadwal:\n";
        foreach ($mkWithJadwal as $mk) {
            echo "     - {$mk}\n";
        }
    }
    
    if (count($mkWithoutJadwal) > 0) {
        echo "\n   ✗ MK yang TIDAK ADA jadwal:\n";
        foreach (array_slice($mkWithoutJadwal, 0, 10) as $mk) {
            echo "     - {$mk}\n";
        }
        if (count($mkWithoutJadwal) > 10) {
            echo "     ... dan " . (count($mkWithoutJadwal) - 10) . " lainnya\n";
        }
    }
}

// 6. Sample Mahasiswa Check
echo "\n6. SAMPLE MAHASISWA:\n";
$mahasiswa = Mahasiswa::where('program_studi_id', $prodi->id)
    ->where('status', 'aktif')
    ->first();

if ($mahasiswa) {
    echo "   Found: {$mahasiswa->nama_lengkap} (NIM: {$mahasiswa->nim})\n";
    
    // Calculate semester
    $tahunMasuk = (int) substr($mahasiswa->nim, 0, 4);
    $currentYear = (int) substr($semester->tahun_akademik, 0, 4);
    $yearDiff = $currentYear - $tahunMasuk;
    
    if ($semester->jenis === 'ganjil') {
        $semesterNumber = ($yearDiff * 2) + 1;
    } else {
        $semesterNumber = ($yearDiff * 2) + 2;
    }
    
    echo "   Tahun Masuk: {$tahunMasuk}\n";
    echo "   Current Year: {$currentYear}\n";
    echo "   Calculated Semester: {$semesterNumber}\n";
    
    // Check MK for this semester
    $mkForSemester = MataKuliah::where('kurikulum_id', $kurikulum->id)
        ->where('semester', $semesterNumber)
        ->where('jenis', 'wajib')
        ->count();
    
    echo "   MK Wajib untuk Semester {$semesterNumber}: {$mkForSemester}\n";
    
    // Check if has jadwal
    $mkIds = MataKuliah::where('kurikulum_id', $kurikulum->id)
        ->where('semester', $semesterNumber)
        ->where('jenis', 'wajib')
        ->pluck('id');
    
    $jadwalForSemester = Jadwal::where('semester_id', $semester->id)
        ->whereIn('mata_kuliah_id', $mkIds)
        ->count();
    
    echo "   Jadwal tersedia: {$jadwalForSemester}\n";
} else {
    echo "   ✗ No active mahasiswa found for this prodi\n";
}

echo "\n=== END DEBUG ===\n";
