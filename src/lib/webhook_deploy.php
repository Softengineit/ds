<?php
declare(strict_types=1);

/**
 * Webhook GitHub : pull automatique + post-deploy.
 * Configuré dans GitHub : Settings → Webhooks → Add webhook
 *  - Payload URL : https://ds.seed-innov.com/_webhook/deploy
 *  - Content type : application/json
 *  - Secret : valeur de GITHUB_WEBHOOK_SECRET dans .env
 *  - Events : Just the push event
 */
function handle_deploy_webhook(): void
{
    $secret = env('GITHUB_WEBHOOK_SECRET', '');
    if ($secret === '') {
        http_response_code(500);
        echo "Webhook désactivé : GITHUB_WEBHOOK_SECRET manquant dans .env\n";
        return;
    }

    $body = file_get_contents('php://input');
    $sigHeader = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
    if ($sigHeader === '') {
        http_response_code(401);
        echo "Signature manquante\n";
        return;
    }

    $expected = 'sha256=' . hash_hmac('sha256', $body, $secret);
    if (!hash_equals($expected, $sigHeader)) {
        http_response_code(401);
        echo "Signature invalide\n";
        webhook_log('FAIL signature mismatch', $_SERVER['REMOTE_ADDR'] ?? '');
        return;
    }

    $event = $_SERVER['HTTP_X_GITHUB_EVENT'] ?? '';
    if ($event === 'ping') {
        echo "pong\n";
        webhook_log('PING ok');
        return;
    }
    if ($event !== 'push') {
        echo "Event ignoré : $event\n";
        return;
    }

    $payload = json_decode($body, true);
    $ref = $payload['ref'] ?? '';
    if ($ref !== 'refs/heads/main') {
        echo "Branch ignorée : $ref\n";
        webhook_log("SKIP non-main branch: $ref");
        return;
    }

    // Exécution du deploy
    $repoDir = realpath(__DIR__ . '/../..');
    $output = [];
    $rc = 0;
    exec('cd ' . escapeshellarg($repoDir) . ' && git fetch --all 2>&1 && git reset --hard origin/main 2>&1', $output, $rc);

    if ($rc === 0) {
        webhook_log("DEPLOY ok", implode(' | ', array_slice($output, -3)));
        echo "Deploy OK\n";
        echo implode("\n", $output);
    } else {
        http_response_code(500);
        webhook_log("DEPLOY FAIL rc=$rc", implode(' | ', $output));
        echo "Deploy KO (rc=$rc)\n";
        echo implode("\n", $output);
    }
}

function webhook_log(string $msg, string $extra = ''): void
{
    $line = sprintf("[%s] %s — %s\n", date('c'), $msg, $extra);
    @file_put_contents(DATA_DIR . '/webhook_deploy.log', $line, FILE_APPEND | LOCK_EX);
}
