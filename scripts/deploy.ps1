# Script de déploiement vers LWS via SSH
# Usage : .\scripts\deploy.ps1
#
# Pré-requis :
#   - ssh-agent démarré, clé id_rsa_lws_steve chargée
#   - IP publique whitelistée dans LWS Firewall SSH
#   - Sous-domaine ds.seed-innov.com déjà créé dans cPanel
#   - DB c2586017c_ds + utilisateur déjà créés
#   - .env serveur configuré (à uploader manuellement la 1ère fois)

$ErrorActionPreference = "Stop"

$LOCAL_ROOT = Split-Path -Parent $PSScriptRoot
$REMOTE_USER = "c2586017c"
$REMOTE_HOST = "seed-innov.com"
$REMOTE_PORT = 22
$REMOTE_PATH = "/home/c2586017c/public_html/ds"

Write-Host "=== Déploiement SEED Digital School ===" -ForegroundColor Cyan
Write-Host "Source : $LOCAL_ROOT"
Write-Host "Cible  : $REMOTE_USER@${REMOTE_HOST}:$REMOTE_PATH"
Write-Host ""

# 1. Vérifier que la clé est chargée
ssh-add -l | Select-String "id_rsa_lws_steve" | Out-Null
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Clé id_rsa_lws_steve non chargée. Lance : ssh-add C:\Users\Admin\.ssh\id_rsa_lws_steve" -ForegroundColor Red
    exit 1
}

# 2. Vérifier que la connexion SSH passe
$test = ssh -p $REMOTE_PORT -o BatchMode=yes -o ConnectTimeout=10 "${REMOTE_USER}@${REMOTE_HOST}" "echo OK"
if ($test -ne "OK") {
    Write-Host "❌ Connexion SSH KO. Vérifier IP whitelist + agent." -ForegroundColor Red
    exit 1
}
Write-Host "✅ Connexion SSH OK" -ForegroundColor Green

# 3. Créer le dossier distant si besoin
ssh -p $REMOTE_PORT "${REMOTE_USER}@${REMOTE_HOST}" "mkdir -p $REMOTE_PATH"

# 4. Uploader (en excluant les fichiers locaux/dev)
$EXCLUDES = @(
    "--exclude=.env",
    "--exclude=.env.local",
    "--exclude=.git/",
    "--exclude=.idea/",
    "--exclude=.vscode/",
    "--exclude=node_modules/",
    "--exclude=vendor/",
    "--exclude=data/inscriptions_backup/*.jsonl",
    "--exclude=*.log"
)

# Si rsync est disponible (via Git Bash / WSL), l'utiliser. Sinon, scp -r.
$rsync = Get-Command rsync -ErrorAction SilentlyContinue
if ($rsync) {
    Write-Host "→ rsync (transfert différentiel)..." -ForegroundColor Cyan
    & rsync -avz --progress @EXCLUDES -e "ssh -p $REMOTE_PORT" "$LOCAL_ROOT/" "${REMOTE_USER}@${REMOTE_HOST}:$REMOTE_PATH/"
} else {
    Write-Host "→ scp (rsync non disponible)..." -ForegroundColor Yellow
    Write-Host "  Pour aller plus vite ensuite, installe rsync (Git Bash, WSL, ou cwRsync)."
    & scp -P $REMOTE_PORT -r "$LOCAL_ROOT/public" "$LOCAL_ROOT/src" "$LOCAL_ROOT/data" "$LOCAL_ROOT/sql" "$LOCAL_ROOT/scripts" "$LOCAL_ROOT/README.md" "${REMOTE_USER}@${REMOTE_HOST}:$REMOTE_PATH/"
}

# 5. Permissions
Write-Host "→ Ajustement des permissions..." -ForegroundColor Cyan
ssh -p $REMOTE_PORT "${REMOTE_USER}@${REMOTE_HOST}" @"
cd $REMOTE_PATH && \
find . -type d -exec chmod 755 {} \; && \
find . -type f -exec chmod 644 {} \; && \
chmod 600 .env 2>/dev/null || echo '.env absent — à créer manuellement'
"@

# 6. Smoke test en ligne
Write-Host "→ Smoke test..." -ForegroundColor Cyan
try {
    $r = Invoke-WebRequest -Uri "https://ds.seed-innov.com/" -UseBasicParsing -TimeoutSec 15
    Write-Host "✅ https://ds.seed-innov.com/ → $($r.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "⚠️  Site non encore accessible : $_" -ForegroundColor Yellow
    Write-Host "   Vérifier que le sous-domaine est bien créé dans cPanel."
}

Write-Host ""
Write-Host "=== Terminé ===" -ForegroundColor Green
