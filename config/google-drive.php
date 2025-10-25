<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Drive Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Google Drive integration
    |
    */

    'enabled' => env('GOOGLE_DRIVE_ENABLED', false),

    'credentials_path' => env('GOOGLE_DRIVE_CREDENTIALS_PATH', 'storage/app/google/credentials.json'),

    'root_folder_id' => env('GOOGLE_DRIVE_ROOT_FOLDER_ID'),

    /*
    |--------------------------------------------------------------------------
    | Folder Structure
    |--------------------------------------------------------------------------
    |
    | Define the folder structure in Google Drive
    |
    */

    'folders' => [
        'pembayaran' => 'Pembayaran',
        'dokumen_mahasiswa' => 'Dokumen-Mahasiswa',
        'spmb' => 'SPMB',
    ],

    /*
    |--------------------------------------------------------------------------
    | File Naming Convention
    |--------------------------------------------------------------------------
    |
    | Pattern: {identifier}_{category}_{timestamp}.{extension}
    | Example: 202301010001_SPP_20251025_150530.pdf
    |
    */

    'naming' => [
        'date_format' => 'Ymd_His',
        'separator' => '_',
    ],
];
