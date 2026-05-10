# SEED Digital School — Site web

Site officiel de **SEED Digital School** (Yaoundé, Cameroun).
Production : https://ds.seed-innov.com

## Stack

- PHP 8.1+ (compatible 8.3 en dev)
- MariaDB / MySQL (cPanel LWS)
- Bootstrap 5.3 (CDN) + CSS local
- Aucune dépendance externe (no Composer needed for runtime, no Node)

## Architecture

```
ds/
├── public/                   ← DocumentRoot du site (index.php = front controller)
│   ├── index.php             ← routeur unique
│   ├── .htaccess             ← URLs propres + HTTPS + cache + WebP auto
│   ├── admin/router.php      ← sous-routeur admin
│   └── assets/               ← CSS, JS, images (JPG + WebP)
├── src/
│   ├── lib/                  ← config, db, auth, csrf, sitemap, inscription
│   └── templates/            ← templates PHP des pages
│       ├── partials/         ← header, footer, banner info-rentree
│       └── admin/            ← templates de l'admin
├── data/
│   ├── content.json          ← TOUT le contenu du site (éditable via /admin/contenu)
│   └── inscriptions_backup/  ← fallback JSONL si DB indisponible
├── sql/schema.sql            ← schéma BDD
├── scripts/
│   ├── install.php           ← install initiale (tables + 1er admin)
│   └── optimize-images.php   ← compression + génération WebP
├── .env.example              ← copier en .env et compléter
└── .gitignore
```

## Routes publiques

| URL | Page |
|---|---|
| `/` | Accueil |
| `/formations` | Liste des cursus |
| `/formations/{slug}` | Fiche formation détaillée (programme par pack) |
| `/packs` | Packs Express / Avancé / Pro + cursus complet |
| `/pack-rapide` | Pack Rapide 6 semaines / 55 000 FCFA |
| `/modules` | Modules digitaux courts (dès 10 000 FCFA) |
| `/inscription` | Formulaire d'inscription (POST → DB + redirect WhatsApp) |
| `/contact` | Page contact + formulaire |
| `/sitemap.xml` | Sitemap auto-généré |
| `/robots.txt` | Robots auto-généré |

## Routes admin (auth requise)

| URL | Description |
|---|---|
| `/admin/login` | Connexion |
| `/admin/dashboard` | Stats + dernières inscriptions |
| `/admin/inscriptions[?statut=X]` | Liste filtable |
| `/admin/inscriptions/{id}` | Détail + statut + notes |
| `/admin/contenu` | Édition de `content.json` (avec backup auto) |
| `/admin/users` | Gestion comptes (rôle `admin` uniquement) |
| `/admin/profile` | Mon profil + changement de mot de passe |

Rôles : `admin` (tout) / `editor` (tout sauf gestion users).

## Développement local

```powershell
# 1. Copier .env.example → .env et adapter (DB locale optionnelle)
# 2. Lancer le serveur :
cd ds
php -S localhost:8765 -t public public/index.php
```

Sans BDD, les inscriptions sont sauvegardées dans `data/inscriptions_backup/YYYY-MM.jsonl`.

## Première installation sur LWS

1. **Créer le sous-domaine** `ds.seed-innov.com` dans cPanel → DocumentRoot pointer sur `~/public_html/ds/public/`.
2. **Créer la base** `c2586017c_ds` + utilisateur `c2586017c_dsapp` dans cPanel → MySQL Databases. Donner ALL PRIVILEGES.
3. **Uploader le projet** (rsync/SCP) vers `~/public_html/ds/`.
4. **Créer le `.env`** côté serveur (à partir de `.env.example`, mettre les vrais credentials DB).
5. **Lancer l'install** :
   ```bash
   cd ~/public_html/ds
   php scripts/install.php
   # ou non-interactif :
   ADMIN_EMAIL=steve@seeds.cm ADMIN_NAME='Steve Nouyep' ADMIN_PASSWORD='xxxxxxxxx' php scripts/install.php
   ```
6. **Vérifier** : ouvrir https://ds.seed-innov.com/, https://ds.seed-innov.com/admin/login

## Mises à jour du contenu

- **Pour Steve / l'équipe** (modifier prix, textes, contacts, formations) : se connecter à `/admin/contenu` et éditer le JSON.
- **Pour l'admin technique** (ajouter une page, refaire le design) : modifier les templates PHP et redéployer.

## Sécurité

- Sessions PHP avec cookie HTTPOnly / SameSite=Lax / Secure (auto si HTTPS).
- CSRF tokens sur tous les formulaires.
- Mots de passe hashés en bcrypt.
- Rate-limiting léger (delay aléatoire) sur login échoué.
- Honeypot anti-bot sur le formulaire d'inscription.
- Headers : X-Content-Type-Options, X-Frame-Options, Referrer-Policy.
- HTTPS forcé via .htaccess.
- `data/`, `src/`, `.env` jamais accessibles publiquement (en dehors de `public/`).

## Backup contenu

Chaque édition via `/admin/contenu` crée un fichier `data/content.backup.YYYYMMDD-HHMMSS.json`. Pour restaurer une version, copier le fichier en `content.json`.

## Données externes

- WhatsApp : `+237 650 187 006`
- Téléphone : `+237 656 193 199`
- Email : `contact@seeds.cm`
- Adresse : Afriland First Bank, étage au-dessus, porte à droite, Yaoundé.
