<?php
/** @var array $content */
$m = $content['modules_digitaux'];
$page_title = $m['titre'];
$page_description = $m['description'];
?>
<section class="ds-hero" style="padding:4rem 0;">
  <div class="container">
    <h1><?= e($m['titre']) ?></h1>
    <p class="lead"><?= e($m['sous_titre']) ?></p>
  </div>
</section>

<section class="ds-section">
  <div class="container">
    <div class="ds-section-title">
      <h2>Apprenez à votre rythme</h2>
      <p class="lead"><?= e($m['description']) ?></p>
    </div>

    <div class="row g-4">
      <?php foreach ($m['packs'] as $pack): ?>
        <div class="col-md-4">
          <div class="ds-pack">
            <div class="ds-pack-header bg-<?= e($pack['couleur']) ?>">
              <h3><?= e($pack['nom']) ?></h3>
              <div class="ds-pack-price">
                <?= $pack['prix_fcfa'] !== null ? e(format_fcfa($pack['prix_fcfa'])) : e($pack['prix_label'] ?? 'Sur demande') ?>
              </div>
              <?php if ($pack['prix_fcfa'] !== null): ?>
                <div class="ds-pack-price-suffix">prix de départ</div>
              <?php endif; ?>
            </div>
            <div class="ds-pack-body">
              <p><?= e($pack['description']) ?></p>
              <p style="font-weight:600;color:var(--ds-dark);margin-top:1rem;">Modules inclus :</p>
              <ul>
                <?php foreach ($pack['modules_inclus'] as $module): ?>
                  <li><?= e($module) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
            <div class="ds-pack-footer">
              <a href="<?= e(whatsapp_url("Bonjour, je suis intéressé(e) par le " . $pack['nom'] . ".")) ?>" target="_blank" rel="noopener" class="ds-btn ds-btn-whatsapp w-100" style="justify-content:center;">Demander des infos</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="ds-section ds-section-light text-center">
  <div class="container">
    <h2>Vous voulez aller plus loin ?</h2>
    <p class="lead text-muted">Si un module vous passionne, basculez sur un cursus complet pour devenir expert.</p>
    <div class="d-flex justify-content-center gap-3 flex-wrap mt-4">
      <a href="<?= e(url('/formations')) ?>" class="ds-btn ds-btn-primary">Voir les cursus</a>
      <a href="<?= e(url('/pack-rapide')) ?>" class="ds-btn ds-btn-accent">Pack Rapide — 6 semaines</a>
    </div>
  </div>
</section>
