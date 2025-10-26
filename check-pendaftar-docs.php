<?php
/**
 * Script untuk debug dokumen pendaftar SPMB
 *
 * Cara pakai:
 * 1. Ganti $nomorPendaftaran dengan nomor yang mau dicek
 * 2. Jalankan: php check-pendaftar-docs.php
 */

// Ganti dengan nomor pendaftaran yang mau dicek
$nomorPendaftaran = 'REG202500003';

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$pendaftar = App\Models\Pendaftar::where('nomor_pendaftaran', $nomorPendaftaran)->first();

if (!$pendaftar) {
    echo "❌ Pendaftar tidak ditemukan!\n";
    exit;
}

echo "✅ Data Pendaftar: {$pendaftar->nama}\n";
echo "   Email: {$pendaftar->email}\n";
echo "==========================================\n\n";

$documents = [
    'Foto 4x6' => ['google_drive_link', 'google_drive_file_id'],
    'Ijazah' => ['ijazah_google_drive_link', 'ijazah_google_drive_id'],
    'Transkrip' => ['transkrip_google_drive_link', 'transkrip_google_drive_id'],
    'KTP' => ['ktp_google_drive_link', 'ktp_google_drive_id'],
    'Kartu Keluarga' => ['kk_google_drive_link', 'kk_google_drive_id'],
    'Akta Kelahiran' => ['akta_google_drive_link', 'akta_google_drive_id'],
    'SKTM' => ['sktm_google_drive_link', 'sktm_google_drive_id'],
];

echo "Dokumen yang ter-upload:\n";
echo "------------------------\n\n";

$totalDocs = 0;
foreach ($documents as $docName => $fields) {
    $linkField = $fields[0];
    $idField = $fields[1];

    $hasLink = !empty($pendaftar->$linkField);
    $hasId = !empty($pendaftar->$idField);

    if ($hasLink || $hasId) {
        $totalDocs++;
        echo "✅ {$docName}: ADA\n";
        if ($hasLink) echo "   - Link: {$pendaftar->$linkField}\n";
        if ($hasId) echo "   - ID: {$pendaftar->$idField}\n";
    } else {
        echo "❌ {$docName}: TIDAK ADA\n";
    }
    echo "\n";
}

echo "\n==========================================\n";
echo "Total dokumen ter-upload: {$totalDocs} / 7\n";

if ($totalDocs < 7) {
    echo "\n⚠️  Beberapa dokumen belum di-upload!\n";
    echo "Kemungkinan:\n";
    echo "1. Pendaftar belum lengkap mengisi form\n";
    echo "2. Upload gagal saat submit form\n";
    echo "3. Draft yang belum di-submit final\n";
}
