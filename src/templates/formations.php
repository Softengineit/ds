<?php
/** @var array $content */
$page_title = "Nos Formations";
$page_description = "Découvrez nos 5 cursus : Secrétariat, Marketing Digital, Programmation Web, Programmation Mobile, Graphisme et Design.";
$formations = array_filter($content['formations'] ?? [], fn($f) => !empty($f['actif']));
usort($formations, fn($a, $b) => ($a['ordre'] ?? 0) <=> ($b['ordre'] ?? 0));
?>
<section class="ds-hero" style="padding:4rem 0;">
  <div class="container">
    <h1>Nos Cursus de Formations</h1>
    <p class="lead">Découvrez nos différents cursus et choisissez celui qui correspond le mieux à vos aspirations professionnelles.</p>
  </div>
</section>

<section class="ds-section ds-section-light">
  <div class="container">
    <div class="row g-4">
      <?php foreach ($formations as $f): ?>
        <div class="col-md-6 col-lg-4">
          <div class="ds-card">
            <img src="<?= e(asset('img/' . $f['image'])) ?>" alt="<?= e($f['titre']) ?>" class="ds-card-img" loading="lazy">
            <div class="ds-card-body">
              <h3><?= e($f['titre']) ?></h3>
              <p><?= e($f['description_courte']) ?></p>
            </div>
            <div class="ds-card-footer">
              <a href="<?= e(url('/formations/' . $f['slug'])) ?>" class="ds-btn ds-btn-primary">Voir détails →</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

      <?php if (!empty($content['cursus_complet']['actif'])): $c = $content['cursus_complet']; ?>
        <div class="col-12 mt-5">
          <div class="ds-card" style="border:2px solid var(--ds-primary);">
            <div class="row g-0">
              <div class="col-md-5">
                <img src="<?= e(asset('img/' . $c['image'])) ?>" alt="<?= e($c['titre']) ?>" class="w-100 h-100" style="object-fit:cover;" loading="lazy">
              </div>
              <div class="col-md-7">
                <div class="ds-card-body">
                  <span class="ds-badge mb-2">12 MOIS · STAGE PRO INCLUS</span>
                  <h3><?= e($c['titre']) ?></h3>
                  <p><?= e($c['description']) ?></p>
                  <ul style="padding-left:1.25rem;line-height:2;">
                    <?php foreach ($c['specs'] as $s): ?>
                      <li><?= e($s) ?></li>
                    <?php endforeach; ?>
                  </ul>
                  <p style="font-size:1.5rem;font-weight:800;color:var(--ds-primary);margin-top:1rem;">
                    <?= e(format_fcfa($c['prix_unique_fcfa'])) ?>
                    <span style="font-size:0.9rem;color:var(--ds-muted);font-weight:500;">
                      ou <?= e(format_fcfa($c['prix_2tranches_total_fcfa'])) ?> en 2 tranches
                    </span>
                  </p>
                  <a href="<?= e(url('/inscription')) ?>" class="ds-btn ds-btn-primary ds-btn-lg">S'inscrire à ce cursus</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
