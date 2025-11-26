<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CEK FOTO MAHASISWA ===\n\n";

$mahasiswas = \App\Models\Mahasiswa::with('user')->take(5)->get();

foreach ($mahasiswas as $m) {
    echo "NIM: {$m->nim}\n";
    echo "Nama: {$m->nama_lengkap}\n";
    echo "Foto: " . ($m->foto ?? 'NULL') . "\n";
    echo "---\n";
}

echo "\n=== CEK FOTO DOSEN ===\n\n";

$dosens = \App\Models\Dosen::with('user')->take(5)->get();

foreach ($dosens as $d) {
    echo "NIDN: {$d->nidn}\n";
    echo "Nama: {$d->nama_lengkap}\n";
    echo "Foto: " . ($d->foto ?? 'NULL') . "\n";
    echo "---\n";
}
