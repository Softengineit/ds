<?php
/** @var array $content */
$pr = $content['pack_rapide'];
$page_title = $pr['nom'];
$page_description = $pr['description'];
?>
<section class="ds-hero" style="padding:4rem 0;background:linear-gradient(135deg, rgba(15,23,42,0.85), rgba(245,158,11,0.65)), url('<?= e(asset('img/' . $pr['image'])) ?>') center/cover;">
  <div class="container">
    <span class="ds-badge mb-3" style="background:#fff;color:var(--ds-dark);"><?= e(format_fcfa($pr['prix_fcfa'])) ?> · <?= e($pr['duree_label']) ?></span>
    <h1><?= e($pr['nom']) ?></h1>
    <p class="lead"><?= e($pr['sous_titre']) ?></p>
  </div>
</section>

<section class="ds-section">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-7">
        <h2>Une initiation digitale en 6 semaines</h2>
        <p style="font-size:1.05rem;"><?= e($pr['description']) ?></p>

        <h3 class="mt-4 mb-3" style="font-size:1.25rem;">Vos objectifs</h3>
        <ul style="padding-left:1.25rem;line-height:2;">
          <?php foreach ($pr['objectifs'] as $obj): ?>
            <li><?= e($obj) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="col-lg-5">
        <div class="ds-pack">
          <div class="ds-pack-header bg-warning">
            <h3><?= e($pr['nom']) ?></h3>
            <div class="ds-pack-price"><?= e(format_fcfa($pr['prix_fcfa'])) ?></div>
            <div class="ds-pack-price-suffix"><?= e($pr['duree_label']) ?> · pack indépendant</div>
          </div>
          <div class="ds-pack-body">
            <ul>
              <li><strong>Durée :</strong> <?= e($pr['duree_label']) ?></li>
              <li><strong>Prix tout compris :</strong> <?= e(format_fcfa($pr['prix_fcfa'])) ?></li>
              <li><strong>Format :</strong> Présentiel + accompagnement</li>
              <li><strong>Niveau :</strong> Débutant complet accepté</li>
            </ul>
          </div>
          <div class="ds-pack-footer">
            <a href="<?= e(url('/inscription?pack=' . urlencode($pr['nom']))) ?>" class="ds-btn ds-btn-accent w-100" style="justify-content:center;">S'inscrire au Pack Rapide</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="ds-section ds-section-light">
  <div class="container">
    <div class="ds-section-title">
      <h2>Au programme</h2>
      <p class="lead">6 modules pour découvrir l'univers digital et construire votre première compétence.</p>
    </div>
    <div class="row g-3">
      <?php foreach ($pr['modules'] as $i => $module): ?>
        <div class="col-md-6 col-lg-4">
          <div class="ds-card" style="height:auto;">
            <div class="ds-card-body">
              <span class="ds-badge mb-2">Module <?= e((string)($i + 1)) ?></span>
              <h5 style="font-size:1.05rem;margin:0;"><?= e($module) ?></h5>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="ds-section ds-section-dark text-center">
  <div class="container">
    <h2>Lancez-vous en 6 semaines.</h2>
    <p class="lead" style="opacity:0.85;">Le Pack Rapide est l'investissement le plus accessible pour entrer dans le numérique.</p>
    <div class="ds-hero-cta mt-4">
      <a href="<?= e(url('/inscription?pack=' . urlencode($pr['nom']))) ?>" class="ds-btn ds-btn-accent ds-btn-lg">S'inscrire pour <?= e(format_fcfa($pr['prix_fcfa'])) ?></a>
      <a href="<?= e(whatsapp_url('Bonjour, je veux plus d\'infos sur le Pack Rapide')) ?>" target="_blank" rel="noopener" class="ds-btn ds-btn-whatsapp ds-btn-lg">Discuter sur WhatsApp</a>
    </div>
  </div>
</section>
