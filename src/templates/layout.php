<?php
/** @var array $content */
/** @var string $page_content */
$page_title = $page_title ?? ($content['site']['name']);
$page_description = $page_description ?? $content['site']['description_meta'];
$page_url = ($_SERVER['HTTPS'] ?? null ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'ds.seed-innov.com') . ($_SERVER['REQUEST_URI'] ?? '/');
?><!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($page_title) ?> — <?= e($content['site']['name']) ?></title>
  <meta name="description" content="<?= e($page_description) ?>">
  <meta name="keywords" content="<?= e($content['site']['keywords_meta']) ?>">
  <meta name="author" content="<?= e($content['site']['name']) ?>">
  <link rel="canonical" href="<?= e($page_url) ?>">

  <!-- Open Graph -->
  <meta property="og:title" content="<?= e($page_title) ?>">
  <meta property="og:description" content="<?= e($page_description) ?>">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= e($page_url) ?>">
  <meta property="og:image" content="<?= e($content['site']['url_canonical']) ?>/assets/img/logo.png">
  <meta property="og:locale" content="fr_FR">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?= e(asset('img/logo.png')) ?>">

  <!-- Bootstrap 5 + fonts + style local -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(asset('css/styles.css')) ?>">

  <!-- JSON-LD EducationalOrganization -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "EducationalOrganization",
    "name": "<?= e($content['site']['name']) ?>",
    "url": "<?= e($content['site']['url_canonical']) ?>",
    "logo": "<?= e($content['site']['url_canonical']) ?>/assets/img/logo.png",
    "description": "<?= e($content['site']['description_meta']) ?>",
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "Yaoundé",
      "addressCountry": "CM"
    },
    "telephone": "<?= e('+' . $content['contact']['whatsapp']) ?>",
    "email": "<?= e($content['contact']['email']) ?>"
  }
  </script>
</head>
<body>
  <?php partial('info-rentree'); ?>
  <?php partial('header'); ?>

  <main>
    <?= $page_content ?>
  </main>

  <?php partial('footer'); ?>

  <a href="<?= e(whatsapp_url('Bonjour SEED Digital School, j\'aimerais avoir plus d\'informations.')) ?>" class="ds-whatsapp-float" target="_blank" rel="noopener" aria-label="Contacter sur WhatsApp">
    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
  </a>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  <script src="<?= e(asset('js/app.js')) ?>" defer></script>
</body>
</html>
