<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║         MANUAL SERVICE TEST                                  ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$service = new \App\Services\SppAutoGenerateService();
$sem6 = \App\Models\Semester::find(6);
$sem1 = \App\Models\Semester::find(1);

echo "Testing service with:\n";
echo "  Current: {$sem1->nama_semester} {$sem1->tahun_akademik}\n";
echo "  Target:  {$sem6->nama_semester} {$sem6->tahun_akademik}\n\n";

$result = $service->generateSppForSemester($sem6, $sem1);

echo "Result:\n";
print_r($result);

echo "\n";

$count = \App\Models\Pembayaran::where('jenis_pembayaran', 'spp')
    ->where('semester_id', 6)
    ->count();

echo "SPP records in database: {$count}\n\n";
