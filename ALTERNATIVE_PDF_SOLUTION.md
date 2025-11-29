# Alternative PDF Solutions (No GD Required)

## Problem
DomPDF requires GD extension which is not enabled/working properly.

## Solution 1: Use TCPDF (RECOMMENDED)

TCPDF doesn't require GD extension and works out of the box.

### Install TCPDF
```bash
composer require elibyy/tcpdf-laravel
```

### Publish Config
```bash
php artisan vendor:publish --provider="Elibyy\TCPDF\ServiceProvider"
```

### Update Controller
```php
use PDF; // Keep same facade

public function downloadPdf($id)
{
    // ... same code ...
    
    // Use TCPDF instead
    $pdf = PDF::setOptions(['defaultFont' => 'times']);
    $pdf->loadView('mahasiswa.khs.pdf', compact('khs', 'khsService', 'logoBase64'));
    $pdf->setPaper('A4', 'portrait');
    
    return $pdf->download($filename);
}
```

## Solution 2: Use mPDF

mPDF also doesn't require GD.

### Install mPDF
```bash
composer require mpdf/mpdf
```

### Update Controller
```php
use Mpdf\Mpdf;

public function downloadPdf($id)
{
    // ... get data ...
    
    $html = view('mahasiswa.khs.pdf', compact('khs', 'khsService', 'logoBase64'))->render();
    
    $mpdf = new Mpdf([
        'format' => 'A4',
        'orientation' => 'P',
        'default_font' => 'times'
    ]);
    
    $mpdf->WriteHTML($html);
    
    return $mpdf->Output($filename, 'D'); // D = download
}
```

## Solution 3: Use Snappy/wkhtmltopdf

Uses system wkhtmltopdf binary (no PHP dependencies).

### Install
```bash
composer require barryvdh/laravel-snappy
```

### Download wkhtmltopdf
https://wkhtmltopdf.org/downloads.html

### Config
```php
// config/snappy.php
'pdf' => [
    'enabled' => true,
    'binary' => 'C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe',
    'options' => [],
],
```

### Controller
```php
use PDF; // Snappy facade

$pdf = PDF::loadView('mahasiswa.khs.pdf', compact(...));
return $pdf->download($filename);
```

## Solution 4: Enable GD Extension (BEST)

### Already Done:
- ✅ php.ini edited: `extension=gd` (line 931)
- ✅ Backup created: php.ini.backup
- ✅ GD module exists: D:\xampp\php\ext\php_gd.dll

### Need to Do:
**RESTART APACHE PROPERLY:**

1. Open XAMPP Control Panel
2. Click **Stop** on Apache → Wait until gray
3. Click **Start** on Apache → Wait until green
4. Verify: http://localhost/siakad-app/public/test-gd.php
   - Should show: "✓ GD Extension is ENABLED!"

### Test Command
```bash
php -m | findstr gd
```
Should output: `gd`

## Quick Decision Tree

```
GD enabled and Apache restarted properly?
├─ YES → Use DomPDF (current solution)
│
└─ NO → Can't restart Apache?
    ├─ YES → Use TCPDF or mPDF (no GD needed)
    │
    └─ NO → Restart Apache and test again
```

## Recommended Action

**TRY THIS FIRST:**
1. Open XAMPP Control Panel
2. Stop Apache completely
3. Start Apache again
4. Test: http://localhost/siakad-app/public/phpinfo.php
5. Search for "gd" → Should show GD section
6. Test KHS PDF download

**IF STILL FAILS:**
Install TCPDF (5 minutes):
```bash
cd D:\AI\siakad\siakad-app
composer require elibyy/tcpdf-laravel
```

Then let me know - I'll update the controller code!
