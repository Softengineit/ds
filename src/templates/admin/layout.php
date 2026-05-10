<?php
/** @var string $page_content */
/** @var array|null $me */
$me = $me ?? (function_exists('auth_user') ? auth_user() : null);
?><!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin — SEED Digital School</title>
  <meta name="robots" content="noindex,nofollow">
  <link rel="icon" type="image/png" href="/assets/img/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root { --side-w: 240px; }
    body { font-family: 'Inter', sans-serif; background: #f1f5f9; }
    .admin-layout { display: flex; min-height: 100vh; }
    .admin-side { width: var(--side-w); background: #0f172a; color: #cbd5e1; padding: 1.5rem 0; flex-shrink: 0; position: sticky; top: 0; height: 100vh; overflow-y: auto; }
    .admin-side .brand { padding: 0 1.5rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .admin-side .brand img { height: 36px; }
    .admin-side .brand-name { color: #fff; font-weight: 700; margin-top: 0.5rem; font-size: 0.95rem; }
    .admin-side nav { padding: 1rem 0; }
    .admin-side nav a { display: block; padding: 0.65rem 1.5rem; color: #cbd5e1; text-decoration: none; border-left: 3px solid transparent; font-size: 0.95rem; }
    .admin-side nav a:hover, .admin-side nav a.active { background: rgba(255,255,255,0.05); color: #fff; border-left-color: #f59e0b; }
    .admin-main { flex: 1; padding: 2rem; min-width: 0; }
    .admin-topbar { background: #fff; padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .admin-card { background: #fff; padding: 1.5rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 1.5rem; }
    .admin-stat { background: #fff; padding: 1.5rem; border-radius: 12px; text-align: center; border-top: 4px solid #0d6efd; }
    .admin-stat-value { font-size: 2rem; font-weight: 800; color: #0f172a; }
    .admin-stat-label { color: #64748b; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.05em; }
    .table-status { font-size: 0.8rem; padding: 0.2rem 0.6rem; border-radius: 4px; }
    .table-status.nouveau { background: #fef3c7; color: #78350f; }
    .table-status.contacte { background: #dbeafe; color: #1e3a8a; }
    .table-status.inscrit { background: #d1fae5; color: #065f46; }
    .table-status.abandonne { background: #fee2e2; color: #991b1b; }
    @media (max-width: 768px) {
      .admin-side { position: fixed; left: -240px; transition: left 0.3s; z-index: 100; }
      .admin-side.open { left: 0; }
      .admin-main { padding: 1rem; }
    }
  </style>
</head>
<body>
<?php
$path = $_SERVER['REQUEST_URI'] ?? '';
function _a(string $p, string $cur): string { return str_starts_with($cur, $p) && $p !== '/admin' ? 'active' : ($p === $cur ? 'active' : ''); }
?>
<?php if (!$me && !str_contains($path, '/admin/login')): ?>
  <div class="container py-5"><?= $page_content ?></div>
<?php elseif (!$me): ?>
  <?= $page_content ?>
<?php else: ?>
<div class="admin-layout">
  <aside class="admin-side">
    <div class="brand">
      <img src="/assets/img/logo.png" alt="SEED">
      <div class="brand-name">Admin Console</div>
    </div>
    <nav>
      <a href="/admin/dashboard" class="<?= _a('/admin/dashboard', $path) ?>">📊 Tableau de bord</a>
      <a href="/admin/inscriptions" class="<?= _a('/admin/inscriptions', $path) ?>">📝 Inscriptions</a>
      <a href="/admin/contenu" class="<?= _a('/admin/contenu', $path) ?>">✏️ Contenu du site</a>
      <?php if (($me['role'] ?? '') === 'admin'): ?>
        <a href="/admin/users" class="<?= _a('/admin/users', $path) ?>">👥 Utilisateurs</a>
      <?php endif; ?>
      <a href="/admin/profile" class="<?= _a('/admin/profile', $path) ?>">⚙️ Mon compte</a>
      <a href="/" target="_blank" rel="noopener">🌐 Voir le site →</a>
      <a href="/admin/logout" style="margin-top:1rem;">🚪 Déconnexion</a>
    </nav>
  </aside>
  <main class="admin-main">
    <div class="admin-topbar">
      <div>
        <strong style="color:#0f172a;"><?= e($me['nom']) ?></strong>
        <span class="badge bg-<?= $me['role'] === 'admin' ? 'danger' : 'primary' ?> ms-2"><?= e($me['role']) ?></span>
      </div>
      <div style="font-size:0.9rem;color:#64748b;"><?= e(date('d/m/Y H:i')) ?></div>
    </div>
    <?= $page_content ?>
  </main>
</div>
<?php endif; ?>
</body>
</html>
