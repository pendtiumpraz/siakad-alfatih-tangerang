<?php
// Generate base64 encoded logo for PDF

$logoPath = __DIR__ . '/public/images/logo-alfatih.png';

if (!file_exists($logoPath)) {
    die("Logo file not found: $logoPath\n");
}

$imageData = file_get_contents($logoPath);
$base64 = base64_encode($imageData);
$mimeType = 'image/png';

$dataUri = "data:$mimeType;base64,$base64";

echo "\n=== LOGO BASE64 DATA URI ===\n\n";
echo "File: $logoPath\n";
echo "Size: " . filesize($logoPath) . " bytes\n";
echo "Base64 Length: " . strlen($base64) . " characters\n\n";

echo "=== USAGE IN BLADE TEMPLATE ===\n\n";
echo '<img src="' . $dataUri . '" alt="Logo STAI Al-Fatih">' . "\n\n";

echo "=== COPY THIS TO USE IN pdf.blade.php ===\n\n";
echo $dataUri . "\n\n";

// Save to file for easy reference
file_put_contents(__DIR__ . '/logo-data-uri.txt', $dataUri);
echo "✓ Saved to: logo-data-uri.txt\n\n";
?>
