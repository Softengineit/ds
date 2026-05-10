<?php
/**
 * Script d'optimisation des images du dossier public/assets/img/
 * - JPG : ré-encode en qualité 82, max 1600px de large
 * - PNG : conserve mais peut être ré-encodé compact
 * - Génère un .webp pour chaque JPG/PNG
 *
 * Usage : php scripts/optimize-images.php
 */

declare(strict_types=1);

$imgDir = __DIR__ . '/../public/assets/img';
$maxWidth = 1600;
$jpgQuality = 82;
$webpQuality = 78;

if (!extension_loaded('gd')) {
    fwrite(STDERR, "❌ GD non chargé\n"); exit(1);
}

$gdInfo = gd_info();
$canWebp = !empty($gdInfo['WebP Support']);
echo "GD WebP support: " . ($canWebp ? 'OUI' : 'NON') . "\n";
echo "----\n";

$totalBefore = 0; $totalAfter = 0; $totalWebp = 0;

foreach (glob($imgDir . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE) as $file) {
    $name = basename($file);
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $sizeBefore = filesize($file);
    $totalBefore += $sizeBefore;

    [$w, $h, $type] = getimagesize($file);
    $img = match ($type) {
        IMAGETYPE_JPEG => imagecreatefromjpeg($file),
        IMAGETYPE_PNG => imagecreatefrompng($file),
        default => null,
    };
    if (!$img) { echo "⏭  $name (type non supporté)\n"; continue; }

    if ($w > $maxWidth) {
        $ratio = $maxWidth / $w;
        $newW = $maxWidth;
        $newH = (int)round($h * $ratio);
        $resized = imagecreatetruecolor($newW, $newH);
        if ($type === IMAGETYPE_PNG) {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
        }
        imagecopyresampled($resized, $img, 0, 0, 0, 0, $newW, $newH, $w, $h);
        imagedestroy($img);
        $img = $resized;
        $w = $newW; $h = $newH;
    }

    // Sauvegarder JPG/PNG ré-encodé
    if ($type === IMAGETYPE_JPEG) {
        imagejpeg($img, $file, $jpgQuality);
    } elseif ($type === IMAGETYPE_PNG) {
        imagepng($img, $file, 6);
    }

    $sizeAfter = filesize($file);
    $totalAfter += $sizeAfter;

    $webpFile = preg_replace('/\.(jpe?g|png)$/i', '.webp', $file);
    $webpSize = 0;
    if ($canWebp) {
        imagewebp($img, $webpFile, $webpQuality);
        $webpSize = filesize($webpFile);
        $totalWebp += $webpSize;
    }
    imagedestroy($img);

    printf(
        "✅ %-15s %dx%d  %s → %s%s\n",
        $name, $w, $h,
        format_size($sizeBefore),
        format_size($sizeAfter),
        $canWebp ? '  | webp: ' . format_size($webpSize) : ''
    );
}

echo "----\n";
printf("TOTAL avant : %s\n", format_size($totalBefore));
printf("TOTAL après : %s (gain JPG/PNG : %.1f%%)\n", format_size($totalAfter), 100 * (1 - $totalAfter / $totalBefore));
if ($totalWebp > 0) {
    printf("TOTAL WebP  : %s (gain WebP vs original : %.1f%%)\n", format_size($totalWebp), 100 * (1 - $totalWebp / $totalBefore));
}

function format_size(int $bytes): string {
    if ($bytes < 1024) return $bytes . ' B';
    if ($bytes < 1024 * 1024) return round($bytes / 1024, 1) . ' KB';
    return round($bytes / 1024 / 1024, 2) . ' MB';
}
