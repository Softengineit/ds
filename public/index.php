<?php
declare(strict_types=1);

// PHP built-in server : servir directement les fichiers statiques existants
if (php_sapi_name() === 'cli-server') {
    $reqPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
    $localFile = __DIR__ . $reqPath;
    if ($reqPath !== '/' && is_file($localFile)) {
        return false;
    }
}

require __DIR__ . '/../src/lib/config.php';
load_env();
require __DIR__ . '/../src/lib/db.php';
require __DIR__ . '/../src/lib/csrf.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$path = '/' . trim($path, '/');
$method = $_SERVER['REQUEST_METHOD'];

if (str_starts_with($path, '/admin')) {
    require __DIR__ . '/../src/lib/auth.php';
    require __DIR__ . '/admin/router.php';
    exit;
}

switch (true) {
    case $path === '/' || $path === '':
        render_template('home');
        break;

    case $path === '/formations':
        render_template('formations');
        break;

    case preg_match('#^/formations/([a-z0-9-]+)$#', $path, $m):
        $f = find_formation($m[1]);
        if (!$f) { http_response_code(404); render_template('404'); break; }
        render_template('formation-detail', ['formation' => $f]);
        break;

    case $path === '/packs':
        render_template('packs');
        break;

    case $path === '/pack-rapide':
        render_template('pack-rapide');
        break;

    case $path === '/modules':
        render_template('modules');
        break;

    case $path === '/inscription':
        if ($method === 'POST') {
            require __DIR__ . '/../src/lib/inscription.php';
            handle_inscription_post();
            break;
        }
        render_template('inscription');
        break;

    case $path === '/contact':
        render_template('contact');
        break;

    case $path === '/sitemap.xml':
        header('Content-Type: application/xml');
        require __DIR__ . '/../src/lib/sitemap.php';
        echo build_sitemap();
        break;

    case $path === '/robots.txt':
        header('Content-Type: text/plain');
        $base = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
        echo "User-agent: *\nAllow: /\nDisallow: /admin\nSitemap: $base/sitemap.xml\n";
        break;

    default:
        http_response_code(404);
        render_template('404');
}
