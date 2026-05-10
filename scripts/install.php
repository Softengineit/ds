<?php
/**
 * Script d'installation initiale.
 * Usage : php scripts/install.php
 *
 * - Crée les tables (idempotent : IF NOT EXISTS)
 * - Crée le premier compte admin si aucun n'existe
 *
 * Variables d'environnement requises (.env) :
 *   DB_HOST, DB_NAME, DB_USER, DB_PASS
 *
 * Variables optionnelles pour bootstrap d'un admin :
 *   ADMIN_EMAIL, ADMIN_NAME, ADMIN_PASSWORD (sinon prompt interactif)
 */

declare(strict_types=1);
require __DIR__ . '/../src/lib/config.php';
load_env();
require __DIR__ . '/../src/lib/db.php';

echo "=== Installation SEED Digital School ===\n\n";

// 1. Tester la connexion DB
try {
    $pdo = db();
    echo "✅ Connexion DB OK : " . env('DB_HOST') . " / " . env('DB_NAME') . "\n";
} catch (Throwable $e) {
    echo "❌ Connexion DB impossible : " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Créer les tables
$schema = file_get_contents(ROOT_DIR . '/sql/schema.sql');
if ($schema === false) { echo "❌ schema.sql introuvable\n"; exit(1); }
// Retirer les commentaires SQL ligne à ligne avant le split
$cleaned = implode("\n", array_filter(
    explode("\n", $schema),
    fn($l) => !str_starts_with(trim($l), '--')
));
foreach (preg_split('/;\s*\n/', $cleaned) as $stmt) {
    $stmt = trim($stmt);
    if ($stmt === '') continue;
    try { $pdo->exec($stmt); echo "✅ " . substr($stmt, 0, 60) . "...\n"; }
    catch (Throwable $e) { echo "⚠️  " . $e->getMessage() . "\n"; }
}

// 3. Créer le premier admin si nécessaire
$count = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
if ($count > 0) {
    echo "\nℹ️  $count utilisateur(s) déjà existant(s) — pas de création de compte.\n";
    exit(0);
}

$email = getenv('ADMIN_EMAIL') ?: readline("Email admin : ");
$nom = getenv('ADMIN_NAME') ?: readline("Nom : ");
$pass = getenv('ADMIN_PASSWORD') ?: readline("Mot de passe (10+ chars) : ");

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 10) {
    echo "❌ Email invalide ou mot de passe trop court.\n";
    exit(1);
}

$hash = password_hash($pass, PASSWORD_BCRYPT);
$pdo->prepare('INSERT INTO users (email, nom, password_hash, role) VALUES (?,?,?,?)')
    ->execute([strtolower($email), $nom, $hash, 'admin']);

echo "\n✅ Compte admin créé : $email\n";
echo "🚀 Tu peux te connecter à : " . env('APP_URL', '') . "/admin/login\n";
