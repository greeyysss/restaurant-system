<?php
// Utility: scan project root for image files and copy them into assets/images
// Usage (CLI): php copy_images.php
// Usage (browser): open http://localhost/restaurant_system/copy_images.php

$root = __DIR__ . DIRECTORY_SEPARATOR;
$destDir = $root . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
if (!is_dir($destDir)) mkdir($destDir, 0755, true);

$extensions = ['jpg','jpeg','png','webp','gif'];

$files = [];
foreach ($extensions as $ext) {
    foreach (glob($root . '*.' . $ext) as $f) $files[] = $f;
}

$copied = [];
foreach ($files as $fullpath) {
    $name = basename($fullpath);
    $lower = strtolower($name);

    // map common misspelling
    if (strpos($lower, 'bruger') !== false) {
        $destName = 'burger' . strrchr($name, '.');
    } elseif (preg_match('/^back\./i', $name)) {
        // treat 'back.*' as background pattern for forms/menu
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $destName1 = 'bg-forms.' . $ext;
        $destName2 = 'bg-menu.' . $ext;
        copy($fullpath, $destDir . $destName1);
        copy($fullpath, $destDir . $destName2);
        $copied[] = $destName1;
        $copied[] = $destName2;
        continue;
    } else {
        // normal product image naming: keep name
        $destName = $name;
    }

    // standardize simple names (lowercase)
    $destName = strtolower($destName);

    copy($fullpath, $destDir . $destName);
    $copied[] = $destName;
}

header('Content-Type: text/plain');
if (empty($copied)) {
    echo "No image files found in project root. Place product/bg images in the project root (e.g. fries.jpg, back.jpg) and re-run this script.\n";
} else {
    echo "Copied: " . implode(', ', $copied) . "\n";
}

echo "Destination: assets/images/\n";

?>
