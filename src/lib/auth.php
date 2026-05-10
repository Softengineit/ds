<?php
declare(strict_types=1);

function auth_start(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }
}

function auth_user(): ?array
{
    auth_start();
    if (empty($_SESSION['user_id'])) return null;
    static $cache = null;
    if ($cache !== null && $cache['id'] === $_SESSION['user_id']) return $cache;
    $stmt = db()->prepare('SELECT id, email, nom, role, created_at FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    $cache = $user ?: null;
    return $cache;
}

function auth_login(string $email, string $password): bool
{
    auth_start();
    $stmt = db()->prepare('SELECT id, password_hash FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([strtolower(trim($email))]);
    $row = $stmt->fetch();
    if (!$row || !password_verify($password, $row['password_hash'])) {
        usleep(random_int(200_000, 600_000));
        return false;
    }
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$row['id'];
    db()->prepare('UPDATE users SET last_login_at = NOW() WHERE id = ?')->execute([$row['id']]);
    return true;
}

function auth_logout(): void
{
    auth_start();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

function auth_require(string $minRole = 'editor'): array
{
    $u = auth_user();
    if (!$u) {
        header('Location: /admin/login');
        exit;
    }
    $rank = ['editor' => 1, 'admin' => 2];
    if (($rank[$u['role']] ?? 0) < ($rank[$minRole] ?? 0)) {
        http_response_code(403);
        echo 'Accès refusé.';
        exit;
    }
    return $u;
}
