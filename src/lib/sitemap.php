<?php
declare(strict_types=1);

function build_sitemap(): string
{
    $base = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
    $urls = ['/', '/formations', '/packs', '/pack-rapide', '/modules', '/inscription', '/contact'];
    foreach (load_content()['formations'] as $f) {
        if (!empty($f['actif'])) $urls[] = '/formations/' . $f['slug'];
    }
    $today = date('Y-m-d');
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($urls as $u) {
        $xml .= "  <url><loc>$base$u</loc><lastmod>$today</lastmod></url>\n";
    }
    $xml .= '</urlset>';
    return $xml;
}
