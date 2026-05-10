<?php
declare(strict_types=1);

const ROOT_DIR = __DIR__ . '/../..';
const SRC_DIR = ROOT_DIR . '/src';
const DATA_DIR = ROOT_DIR . '/data';
const TEMPLATES_DIR = SRC_DIR . '/templates';
const CONTENT_FILE = DATA_DIR . '/content.json';

function load_env(): void
{
    $envFile = ROOT_DIR . '/.env';
    if (!is_file($envFile)) {
        return;
    }
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        [$k, $v] = array_pad(explode('=', $line, 2), 2, '');
        $k = trim($k); $v = trim($v, " \t\"'");
        if ($k !== '' && getenv($k) === false) {
            putenv("$k=$v");
            $_ENV[$k] = $v;
        }
    }
}

function env(string $key, ?string $default = null): ?string
{
    $val = getenv($key);
    return $val === false ? $default : $val;
}

function load_content(): array
{
    static $cache = null;
    if ($cache !== null) return $cache;
    $raw = file_get_contents(CONTENT_FILE);
    if ($raw === false) {
        throw new RuntimeException('content.json introuvable');
    }
    $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    $cache = $data;
    return $data;
}

function save_content(array $data): bool
{
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) return false;
    return file_put_contents(CONTENT_FILE, $json, LOCK_EX) !== false;
}

function find_formation(string $slug): ?array
{
    foreach (load_content()['formations'] as $f) {
        if ($f['slug'] === $slug) return $f;
    }
    return null;
}

function format_fcfa(?int $amount): string
{
    if ($amount === null) return '—';
    return number_format($amount, 0, ',', ' ') . ' FCFA';
}

function whatsapp_url(string $message = ''): string
{
    $contact = load_content()['contact'];
    $base = 'https://wa.me/' . $contact['whatsapp'];
    return $message === '' ? $base : $base . '?text=' . rawurlencode($message);
}

function asset(string $path): string
{
    return '/assets/' . ltrim($path, '/');
}

function url(string $path = ''): string
{
    return '/' . ltrim($path, '/');
}

function e(?string $value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function render_template(string $template, array $data = []): void
{
    $content = load_content();
    extract($data, EXTR_SKIP);
    $contentTpl = TEMPLATES_DIR . "/$template.php";
    if (!is_file($contentTpl)) {
        http_response_code(500);
        echo "Template introuvable : $template";
        return;
    }
    ob_start();
    include $contentTpl;
    $page_content = ob_get_clean();
    include TEMPLATES_DIR . '/layout.php';
}

function partial(string $name, array $data = []): void
{
    $content = load_content();
    extract($data, EXTR_SKIP);
    $file = TEMPLATES_DIR . "/partials/$name.php";
    if (is_file($file)) include $file;
}

function date_rentree_auto(): string
{
    $date = new DateTimeImmutable('first day of next month');
    while ((int)$date->format('N') !== 1) {
        $date = $date->modify('+1 day');
    }
    $months = [1=>'janvier',2=>'février',3=>'mars',4=>'avril',5=>'mai',6=>'juin',7=>'juillet',8=>'août',9=>'septembre',10=>'octobre',11=>'novembre',12=>'décembre'];
    $jours = [1=>'lundi',2=>'mardi',3=>'mercredi',4=>'jeudi',5=>'vendredi',6=>'samedi',7=>'dimanche'];
    return sprintf('%s %d %s %s',
        $jours[(int)$date->format('N')],
        (int)$date->format('j'),
        $months[(int)$date->format('n')],
        $date->format('Y')
    );
}
