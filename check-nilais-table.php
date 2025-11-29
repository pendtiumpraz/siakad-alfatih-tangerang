<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔍 Checking nilais table structure...\n\n";

$columns = DB::select("SHOW COLUMNS FROM nilais");

foreach ($columns as $column) {
    $nullable = $column->Null === 'YES' ? '✅ NULLABLE' : '❌ NOT NULL';
    $default = $column->Default ?? 'no default';
    
    echo sprintf(
        "%-20s | %-15s | %s | Default: %s\n",
        $column->Field,
        $column->Type,
        $nullable,
        $default
    );
    
    if ($column->Field === 'dosen_id') {
        echo "\n";
        if ($column->Null === 'YES') {
            echo "✅ SUCCESS! dosen_id is now NULLABLE!\n";
        } else {
            echo "❌ ERROR! dosen_id is still NOT NULL!\n";
        }
        echo "\n";
    }
}
