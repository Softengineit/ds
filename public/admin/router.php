<?php
declare(strict_types=1);

/** @var string $path  ex: /admin/login, /admin/inscriptions/42 */

$adminPath = substr($path, 6); // strip "/admin"
$adminPath = '/' . trim($adminPath, '/');
$method = $_SERVER['REQUEST_METHOD'];

// Routes publiques (login)
if ($adminPath === '/login' || $adminPath === '/') {
    if ($adminPath === '/' && auth_user()) {
        header('Location: /admin/dashboard');
        exit;
    }
    if ($method === 'POST' && $adminPath === '/login') {
        if (!csrf_check($_POST['_csrf'] ?? null)) {
            render_admin('login', ['error' => 'Token CSRF invalide.']);
            exit;
        }
        if (auth_login((string)($_POST['email'] ?? ''), (string)($_POST['password'] ?? ''))) {
            header('Location: /admin/dashboard');
            exit;
        }
        render_admin('login', ['error' => 'Email ou mot de passe incorrect.', 'old_email' => $_POST['email'] ?? '']);
        exit;
    }
    render_admin('login');
    exit;
}

if ($adminPath === '/logout') {
    auth_logout();
    header('Location: /admin/login');
    exit;
}

// À partir d'ici, auth requise
$me = auth_require('editor');

if ($adminPath === '/dashboard') {
    $stats = [
        'total_inscriptions' => (int)db()->query('SELECT COUNT(*) FROM inscriptions')->fetchColumn(),
        'nouveaux' => (int)db()->query("SELECT COUNT(*) FROM inscriptions WHERE statut = 'nouveau'")->fetchColumn(),
        'derniers' => db()->query('SELECT id, nom, email, formation, created_at, statut FROM inscriptions ORDER BY created_at DESC LIMIT 5')->fetchAll(),
    ];
    render_admin('dashboard', ['me' => $me, 'stats' => $stats]);
    exit;
}

if ($adminPath === '/inscriptions') {
    $statut = $_GET['statut'] ?? '';
    $where = ''; $params = [];
    if (in_array($statut, ['nouveau','contacte','inscrit','abandonne'], true)) {
        $where = 'WHERE statut = ?'; $params = [$statut];
    }
    $stmt = db()->prepare("SELECT * FROM inscriptions $where ORDER BY created_at DESC LIMIT 200");
    $stmt->execute($params);
    render_admin('inscriptions', ['me' => $me, 'rows' => $stmt->fetchAll(), 'statut' => $statut]);
    exit;
}

if (preg_match('#^/inscriptions/(\d+)$#', $adminPath, $m)) {
    $id = (int)$m[1];
    if ($method === 'POST') {
        if (!csrf_check($_POST['_csrf'] ?? null)) { http_response_code(400); echo 'CSRF'; exit; }
        $newStatut = $_POST['statut'] ?? '';
        $notes = $_POST['notes_internes'] ?? '';
        if (in_array($newStatut, ['nouveau','contacte','inscrit','abandonne'], true)) {
            db()->prepare('UPDATE inscriptions SET statut = ?, notes_internes = ? WHERE id = ?')
                ->execute([$newStatut, $notes, $id]);
            audit_log($me, 'update_inscription', "id=$id", "statut=$newStatut");
        }
        header("Location: /admin/inscriptions/$id");
        exit;
    }
    $stmt = db()->prepare('SELECT * FROM inscriptions WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) { http_response_code(404); echo 'Inscription introuvable'; exit; }
    render_admin('inscription_detail', ['me' => $me, 'row' => $row]);
    exit;
}

if ($adminPath === '/contenu') {
    if ($method === 'POST') {
        if (!csrf_check($_POST['_csrf'] ?? null)) { http_response_code(400); echo 'CSRF'; exit; }
        $raw = $_POST['content_json'] ?? '';
        try {
            $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($decoded)) throw new RuntimeException('JSON doit être un objet.');
            // backup
            @copy(CONTENT_FILE, DATA_DIR . '/content.backup.' . date('Ymd-His') . '.json');
            save_content($decoded);
            audit_log($me, 'update_content', 'content.json', 'OK');
            header('Location: /admin/contenu?ok=1'); exit;
        } catch (Throwable $e) {
            render_admin('contenu', ['me' => $me, 'raw' => $raw, 'error' => 'JSON invalide : ' . $e->getMessage()]);
            exit;
        }
    }
    render_admin('contenu', ['me' => $me, 'ok' => isset($_GET['ok'])]);
    exit;
}

// Gestion des utilisateurs (admin only)
if ($adminPath === '/users') {
    auth_require('admin');
    if ($method === 'POST') {
        if (!csrf_check($_POST['_csrf'] ?? null)) { http_response_code(400); echo 'CSRF'; exit; }
        $action = $_POST['action'] ?? '';
        if ($action === 'create') {
            $email = strtolower(trim((string)($_POST['email'] ?? '')));
            $nom = trim((string)($_POST['nom'] ?? ''));
            $role = in_array($_POST['role'] ?? '', ['editor','admin'], true) ? $_POST['role'] : 'editor';
            $password = (string)($_POST['password'] ?? '');
            if (filter_var($email, FILTER_VALIDATE_EMAIL) && $nom !== '' && strlen($password) >= 10) {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                try {
                    db()->prepare('INSERT INTO users (email, nom, password_hash, role) VALUES (?,?,?,?)')
                        ->execute([$email, $nom, $hash, $role]);
                    audit_log($me, 'create_user', $email, "role=$role");
                } catch (Throwable $e) { /* email dupliqué */ }
            }
        } elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0 && $id !== (int)$me['id']) {
                db()->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
                audit_log($me, 'delete_user', "id=$id");
            }
        } elseif ($action === 'reset_password') {
            $id = (int)($_POST['id'] ?? 0);
            $newPass = (string)($_POST['new_password'] ?? '');
            if ($id > 0 && strlen($newPass) >= 10) {
                $hash = password_hash($newPass, PASSWORD_BCRYPT);
                db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?')->execute([$hash, $id]);
                audit_log($me, 'reset_password', "id=$id");
            }
        }
        header('Location: /admin/users'); exit;
    }
    $rows = db()->query('SELECT id, email, nom, role, last_login_at, created_at FROM users ORDER BY created_at DESC')->fetchAll();
    render_admin('users', ['me' => $me, 'rows' => $rows]);
    exit;
}

if ($adminPath === '/profile') {
    if ($method === 'POST') {
        if (!csrf_check($_POST['_csrf'] ?? null)) { http_response_code(400); echo 'CSRF'; exit; }
        $current = (string)($_POST['current_password'] ?? '');
        $new = (string)($_POST['new_password'] ?? '');
        $stmt = db()->prepare('SELECT password_hash FROM users WHERE id = ?');
        $stmt->execute([$me['id']]);
        $row = $stmt->fetch();
        if ($row && password_verify($current, $row['password_hash']) && strlen($new) >= 10) {
            db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
                ->execute([password_hash($new, PASSWORD_BCRYPT), $me['id']]);
            audit_log($me, 'change_password', 'self');
            header('Location: /admin/profile?ok=1'); exit;
        }
        render_admin('profile', ['me' => $me, 'error' => 'Mot de passe actuel incorrect ou nouveau trop court (min 10 caractères).']);
        exit;
    }
    render_admin('profile', ['me' => $me, 'ok' => isset($_GET['ok'])]);
    exit;
}

http_response_code(404);
echo 'Page admin introuvable.';

// ---------- Helpers admin ----------
function render_admin(string $template, array $data = []): void
{
    $content = load_content();
    extract($data, EXTR_SKIP);
    $contentTpl = TEMPLATES_DIR . "/admin/$template.php";
    if (!is_file($contentTpl)) { http_response_code(500); echo "Template admin introuvable : $template"; return; }
    ob_start(); include $contentTpl; $page_content = ob_get_clean();
    include TEMPLATES_DIR . '/admin/layout.php';
}

function audit_log(?array $user, string $action, string $target = '', string $details = ''): void
{
    try {
        db()->prepare('INSERT INTO audit_log (user_id, user_email, action, target, details, ip) VALUES (?,?,?,?,?,?)')
            ->execute([
                $user['id'] ?? null,
                $user['email'] ?? null,
                $action, $target, $details,
                $_SERVER['REMOTE_ADDR'] ?? '',
            ]);
    } catch (Throwable $e) { /* silencieux */ }
}
