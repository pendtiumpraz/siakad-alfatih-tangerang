#!/usr/bin/env php
<?php

/**
 * Script to check mata_kuliah data in database
 * Run: php check_mata_kuliah.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CHECKING MATA KULIAH DATA ===\n\n";

// Check total records
$total = DB::table('mata_kuliahs')->count();
echo "Total mata kuliah: {$total}\n\n";

// Check first 5 records
echo "=== FIRST 5 MATA KULIAH ===\n";
$mataKuliahs = DB::table('mata_kuliahs')
    ->select('id', 'kode_mk', 'nama_mk', 'sks', 'semester', 'jenis', 'kurikulum_id')
    ->limit(5)
    ->get();

foreach ($mataKuliahs as $mk) {
    echo "ID: {$mk->id}\n";
    echo "  Kode MK: '{$mk->kode_mk}'\n";
    echo "  Nama MK: '{$mk->nama_mk}'\n";
    echo "  SKS: {$mk->sks}\n";
    echo "  Semester: {$mk->semester}\n";
    echo "  Jenis: {$mk->jenis}\n";
    echo "  Kurikulum ID: {$mk->kurikulum_id}\n";
    echo "  ---\n";
}

// Check for empty nama_mk
echo "\n=== CHECKING FOR EMPTY FIELDS ===\n";
$emptyKode = DB::table('mata_kuliahs')->where('kode_mk', '')->orWhereNull('kode_mk')->count();
$emptyNama = DB::table('mata_kuliahs')->where('nama_mk', '')->orWhereNull('nama_mk')->count();
echo "Empty kode_mk: {$emptyKode}\n";
echo "Empty nama_mk: {$emptyNama}\n";

// Check STAI AL-FATIH mata kuliah
echo "\n=== STAI AL-FATIH MATA KULIAH ===\n";
$staiFatih = DB::table('mata_kuliahs as mk')
    ->join('kurikulums as k', 'mk.kurikulum_id', '=', 'k.id')
    ->join('program_studis as ps', 'k.program_studi_id', '=', 'ps.id')
    ->where('ps.kode_prodi', 'LIKE', 'IQT-%')
    ->orWhere('ps.kode_prodi', 'LIKE', 'HES-%')
    ->select('mk.id', 'mk.kode_mk', 'mk.nama_mk', 'ps.nama_prodi', 'ps.kode_prodi')
    ->limit(10)
    ->get();

foreach ($staiFatih as $mk) {
    echo "Program: {$mk->kode_prodi} - {$mk->nama_prodi}\n";
    echo "  Kode MK: '{$mk->kode_mk}'\n";
    echo "  Nama MK: '{$mk->nama_mk}'\n";
    echo "  ---\n";
}

echo "\n=== DONE ===\n";
