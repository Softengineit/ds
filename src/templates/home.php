<?php
/** @var array $content */
$page_title = "Accueil";
$page_description = $content['site']['tagline'];
?>
<section class="ds-hero">
  <div class="container">
    <span class="ds-badge mb-3"><?= e($content['site']['slogan']) ?></span>
    <h1><?= e($content['site']['name']) ?></h1>
    <p class="lead"><?= e($content['site']['tagline']) ?></p>
    <div class="ds-hero-cta">
      <a href="<?= e(url('/formations')) ?>" class="ds-btn ds-btn-accent ds-btn-lg">Découvrir nos formations</a>
      <a href="<?= e(url('/inscription')) ?>" class="ds-btn ds-btn-outline ds-btn-lg" style="color:#fff;border-color:#fff;">S'inscrire maintenant</a>
    </div>
  </div>
</section>

<section class="ds-section">
  <div class="container">
    <div class="ds-section-title">
      <h2>Nos Cursus de Formations</h2>
      <p class="lead">5 cursus complets pour bâtir votre carrière dans les métiers du numérique.</p>
    </div>
    <div class="row g-4">
      <?php
      $formations = array_filter($content['formations'] ?? [], fn($f) => !empty($f['actif']));
      usort($formations, fn($a, $b) => ($a['ordre'] ?? 0) <=> ($b['ordre'] ?? 0));
      foreach ($formations as $f):
      ?>
        <div class="col-md-6 col-lg-4">
          <div class="ds-card">
            <img src="<?= e(asset('img/' . $f['image'])) ?>" alt="<?= e($f['titre']) ?>" class="ds-card-img" loading="lazy">
            <div class="ds-card-body">
              <h3><?= e($f['titre']) ?></h3>
              <p><?= e($f['description_courte']) ?></p>
            </div>
            <div class="ds-card-footer">
              <a href="<?= e(url('/formations/' . $f['slug'])) ?>" class="ds-btn ds-btn-primary">En savoir plus →</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="ds-section ds-section-light">
  <div class="container">
    <div class="ds-section-title">
      <h2>Choisissez votre rythme</h2>
      <p class="lead">Du module court à 10 000 FCFA au cursus complet de 12 mois — il y a une formation pour chaque parcours.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="ds-card text-center">
          <div class="ds-card-body">
            <span class="ds-badge mb-2">À partir de 10 000 FCFA</span>
            <h3>Modules courts</h3>
            <p>Word, Excel, PowerPoint, Photoshop, web dev… apprenez un outil à la fois.</p>
            <a href="<?= e(url('/modules')) ?>" class="ds-btn ds-btn-primary">Voir les modules</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="ds-card text-center" style="border:2px solid var(--ds-accent);">
          <div class="ds-card-body">
            <span class="ds-badge mb-2"><?= e(format_fcfa($content['pack_rapide']['prix_fcfa'])) ?> · 6 semaines</span>
            <h3>Pack Rapide</h3>
            <p><?= e($content['pack_rapide']['sous_titre']) ?>. Idéal pour s'initier rapidement avant de choisir un cursus long.</p>
            <a href="<?= e(url('/pack-rapide')) ?>" class="ds-btn ds-btn-accent">Découvrir</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="ds-card text-center">
          <div class="ds-card-body">
            <span class="ds-badge mb-2">2, 4 ou 6 mois</span>
            <h3>Cursus longs</h3>
            <p>Express, Avancé ou Pro — choisissez la profondeur qui vous correspond.</p>
            <a href="<?= e(url('/packs')) ?>" class="ds-btn ds-btn-primary">Voir les packs</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="ds-section ds-section-dark">
  <div class="container text-center">
    <h2>Prêt à transformer votre carrière ?</h2>
    <p class="lead" style="opacity:0.85;max-width:720px;margin:1rem auto 2rem;">
      Rejoignez les centaines de jeunes qui se forment chez nous chaque année.
    </p>
    <div class="ds-hero-cta">
      <a href="<?= e(url('/inscription')) ?>" class="ds-btn ds-btn-accent ds-btn-lg">S'inscrire maintenant</a>
      <a href="<?= e(whatsapp_url()) ?>" target="_blank" rel="noopener" class="ds-btn ds-btn-whatsapp ds-btn-lg">Discuter sur WhatsApp</a>
    </div>
  </div>
</section>
