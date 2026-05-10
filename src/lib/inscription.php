<?php
declare(strict_types=1);

function handle_inscription_post(): void
{
    if (!csrf_check($_POST['_csrf'] ?? null)) {
        http_response_code(400);
        render_template('inscription', ['errors' => ['Token CSRF invalide. Recharge la page et réessaie.']]);
        return;
    }

    $data = [
        'nom' => trim((string)($_POST['nom'] ?? '')),
        'email' => strtolower(trim((string)($_POST['email'] ?? ''))),
        'telephone' => trim((string)($_POST['telephone'] ?? '')),
        'formation' => trim((string)($_POST['formation'] ?? '')),
        'pack' => trim((string)($_POST['pack'] ?? '')),
        'groupe' => trim((string)($_POST['groupe'] ?? '')),
        'message' => trim((string)($_POST['message'] ?? '')),
    ];

    $errors = [];
    if ($data['nom'] === '' || mb_strlen($data['nom']) < 2) $errors[] = 'Nom requis (2 caractères minimum).';
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide.';
    if (!preg_match('/^\+?[0-9 ]{6,20}$/', $data['telephone'])) $errors[] = 'Numéro de téléphone invalide.';
    if ($data['formation'] === '') $errors[] = 'Choisis une formation.';

    // Honeypot anti-bot
    if (!empty($_POST['website'])) $errors[] = 'Erreur suspectée (bot).';

    if ($errors) {
        render_template('inscription', ['errors' => $errors, 'old' => $data]);
        return;
    }

    $saved_in_db = false;
    try {
        $pdo = db();
        $stmt = $pdo->prepare(
            'INSERT INTO inscriptions (nom, email, telephone, formation, pack, groupe, message, ip, user_agent, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())'
        );
        $stmt->execute([
            $data['nom'], $data['email'], $data['telephone'],
            $data['formation'], $data['pack'], $data['groupe'], $data['message'],
            $_SERVER['REMOTE_ADDR'] ?? '', substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
        ]);
        $saved_in_db = true;
    } catch (Throwable $e) {
        // Fallback : sauvegarder dans un fichier JSON pour ne jamais perdre l'inscription
        $backupFile = DATA_DIR . '/inscriptions_backup/' . date('Y-m') . '.jsonl';
        $line = json_encode(array_merge($data, ['ts' => date('c'), 'error' => $e->getMessage()]), JSON_UNESCAPED_UNICODE);
        @file_put_contents($backupFile, $line . "\n", FILE_APPEND | LOCK_EX);
    }

    // Redirection WhatsApp avec récap
    $msg = "Bonjour, je souhaite m'inscrire à une formation chez SEED Digital School :\n"
        . "Nom : {$data['nom']}\n"
        . "Email : {$data['email']}\n"
        . "Téléphone : {$data['telephone']}\n"
        . "Formation : {$data['formation']}\n"
        . ($data['pack'] !== '' ? "Pack : {$data['pack']}\n" : '')
        . ($data['groupe'] !== '' ? "Groupe : {$data['groupe']}\n" : '')
        . ($data['message'] !== '' ? "Message : {$data['message']}\n" : '');
    header('Location: ' . whatsapp_url($msg), true, 303);
    exit;
}
