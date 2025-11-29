<?php
echo "<h2>PHP GD Extension Test</h2>";

if (extension_loaded('gd')) {
    echo "<p style='color: green; font-weight: bold;'>✓ GD Extension is ENABLED!</p>";
    
    $info = gd_info();
    echo "<h3>GD Information:</h3>";
    echo "<pre>";
    print_r($info);
    echo "</pre>";
    
    echo "<h3>Supported Image Formats:</h3>";
    echo "<ul>";
    echo "<li>PNG Support: " . (function_exists('imagecreatefrompng') ? '✓ Yes' : '✗ No') . "</li>";
    echo "<li>JPEG Support: " . (function_exists('imagecreatefromjpeg') ? '✓ Yes' : '✗ No') . "</li>";
    echo "<li>GIF Support: " . (function_exists('imagecreatefromgif') ? '✓ Yes' : '✗ No') . "</li>";
    echo "<li>WebP Support: " . (function_exists('imagecreatefromwebp') ? '✓ Yes' : '✗ No') . "</li>";
    echo "</ul>";
    
    echo "<p style='color: green;'><strong>STATUS: KHS PDF seharusnya bisa generate dengan logo!</strong></p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>✗ GD Extension is NOT enabled!</p>";
    echo "<p>Please restart Apache to apply php.ini changes.</p>";
    echo "<p>Or manually enable in: <code>D:\\xampp\\php\\php.ini</code></p>";
}

echo "<hr>";
echo "<h3>PHP Version:</h3>";
echo "<p>" . phpversion() . "</p>";

echo "<h3>All Loaded Extensions:</h3>";
echo "<pre>";
print_r(get_loaded_extensions());
echo "</pre>";
?>
