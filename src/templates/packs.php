<?php
/** @var array $content */
$page_title = "Packs de Formation";
$page_description = "3 packs pour structurer votre apprentissage : Express (2 mois), Avancé (4 mois), Pro (6 mois avec stage).";
$packs = array_filter($content['packs_principaux'], fn($p) => !empty($p['actif']));
usort($packs, fn($a, $b) => ($a['ordre'] ?? 0) <=> ($b['ordre'] ?? 0));
?>
<section class="ds-hero" style="padding:4rem 0;">
  <div class="container">
    <h1>Packs de Formation</h1>
    <p class="lead">Choisissez le pack qui correspond à vos objectifs et à votre rythme.</p>
  </div>
</section>

<section class="ds-section">
  <div class="container">
    <div class="row g-4">
      <?php foreach ($packs as $pack): ?>
        <div class="col-md-4">
          <div class="ds-pack">
            <div class="ds-pack-header bg-<?= e($pack['couleur']) ?>">
              <h3><?= e($pack['nom']) ?></h3>
              <div class="ds-pack-price"><?= e(format_fcfa($pack['prix_unique_fcfa'])) ?></div>
              <div class="ds-pack-price-suffix"><?= e($pack['duree_label']) ?> · ou <?= e(format_fcfa($pack['prix_2tranches_total_fcfa'])) ?> en 2 tranches</div>
            </div>
            <div class="ds-pack-body">
              <p><?= e($pack['description']) ?></p>
              <ul>
                <li><strong>Durée :</strong> <?= e($pack['duree_label']) ?></li>
                <li><strong>Tranche unique :</strong> <?= e(format_fcfa($pack['prix_unique_fcfa'])) ?></li>
                <li><strong>2 tranches :</strong> <?= e(format_fcfa($pack['tranche_1_fcfa'])) ?> + <?= e(format_fcfa($pack['tranche_2_fcfa'])) ?></li>
                <li><strong>Total 2 tranches :</strong> <?= e(format_fcfa($pack['prix_2tranches_total_fcfa'])) ?></li>
              </ul>
            </div>
            <div class="ds-pack-footer">
              <a href="<?= e(url('/inscription?pack=' . urlencode($pack['nom']))) ?>" class="ds-btn ds-btn-primary w-100" style="background:var(--bs-<?= e($pack['couleur']) ?>);justify-content:center;">S'inscrire</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if (!empty($content['cursus_complet']['actif'])): $c = $content['cursus_complet']; ?>
      <div class="mt-5 p-4 p-md-5 rounded text-white" style="background:linear-gradient(135deg, var(--ds-primary) 0%, var(--ds-primary-dark) 100%); border-radius:var(--ds-radius);">
        <div class="row align-items-center">
          <div class="col-md-8">
            <span class="ds-badge mb-2" style="background:rgba(245,158,11,0.25);color:#fff;">CURSUS COMPLET</span>
            <h3 style="color:#fff;"><?= e($c['titre']) ?></h3>
            <p style="opacity:0.95;"><?= e($c['description']) ?></p>
            <p style="font-size:1.5rem;font-weight:800;"><?= e(format_fcfa($c['prix_unique_fcfa'])) ?> <span style="font-size:1rem;opacity:0.85;">ou <?= e(format_fcfa($c['prix_2tranches_total_fcfa'])) ?> en 2 tranches</span></p>
          </div>
          <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="<?= e(url('/inscription?formation=' . urlencode($c['titre']))) ?>" class="ds-btn ds-btn-accent ds-btn-lg">Postuler →</a>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="ds-section ds-section-light">
  <div class="container text-center">
    <h2>Vous cherchez plus court, ou plus ciblé ?</h2>
    <p class="lead text-muted">Découvrez nos alternatives plus rapides ou modulaires.</p>
    <div class="d-flex justify-content-center gap-3 flex-wrap mt-4">
      <a href="<?= e(url('/pack-rapide')) ?>" class="ds-btn ds-btn-accent">Pack Rapide — 6 semaines / 55 000 F</a>
      <a href="<?= e(url('/modules')) ?>" class="ds-btn ds-btn-outline">Modules courts — dès 10 000 F</a>
    </div>
  </div>
</section>
