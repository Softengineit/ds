<?php
/** @var array $content */
/** @var array $formation */
$f = $formation;
$page_title = $f['titre'];
$page_description = $f['description_courte'];
$packs = $content['packs_principaux'];
?>
<section class="ds-hero" style="padding:4rem 0;">
  <div class="container">
    <h1>Cursus : <?= e($f['titre']) ?></h1>
    <p class="lead"><?= e($f['lead']) ?></p>
  </div>
</section>

<section class="ds-section">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-6">
        <h2 class="mb-3">Présentation du cursus</h2>
        <?php foreach ($f['presentation'] as $p): ?>
          <p style="font-size:1.05rem;"><?= e($p) ?></p>
        <?php endforeach; ?>
      </div>
      <div class="col-lg-6">
        <img src="<?= e(asset('img/' . $f['image'])) ?>" alt="<?= e($f['titre']) ?>" class="img-fluid rounded shadow" loading="lazy" style="border-radius:var(--ds-radius);">
      </div>
    </div>
  </div>
</section>

<section class="ds-section ds-section-light">
  <div class="container">
    <div class="ds-section-title">
      <h2>Détails du programme</h2>
      <p class="lead">Choisissez le pack qui correspond à votre ambition et votre disponibilité.</p>
    </div>

    <div class="accordion" id="programAccordion">
      <?php foreach ($packs as $i => $pack):
        $packSlug = $pack['slug'];
        $programme = $f['programme'][$packSlug] ?? [];
        $headingId = 'heading' . ucfirst($packSlug);
        $collapseId = 'collapse' . ucfirst($packSlug);
        $expanded = $i === 0 ? 'true' : 'false';
        $btnClass = $i === 0 ? 'accordion-button' : 'accordion-button collapsed';
        $collapseClass = $i === 0 ? 'accordion-collapse collapse show' : 'accordion-collapse collapse';
      ?>
        <div class="accordion-item">
          <h3 class="accordion-header" id="<?= e($headingId) ?>">
            <button class="<?= e($btnClass) ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?= e($collapseId) ?>" aria-expanded="<?= e($expanded) ?>" aria-controls="<?= e($collapseId) ?>">
              <strong><?= e($pack['nom']) ?></strong>&nbsp;— <?= e($pack['duree_label']) ?> &nbsp;<span style="opacity:0.75;">(à partir de <?= e(format_fcfa($pack['prix_unique_fcfa'])) ?>)</span>
            </button>
          </h3>
          <div id="<?= e($collapseId) ?>" class="<?= e($collapseClass) ?>" aria-labelledby="<?= e($headingId) ?>" data-bs-parent="#programAccordion">
            <div class="accordion-body">
              <?php foreach ($programme as $bloc): ?>
                <strong><?= e($bloc['label']) ?> :</strong>
                <ul>
                  <?php foreach ($bloc['items'] as $item): ?>
                    <li><?= e($item) ?></li>
                  <?php endforeach; ?>
                </ul>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="text-center mt-5">
      <a href="<?= e(url('/inscription?formation=' . urlencode($f['titre']))) ?>" class="ds-btn ds-btn-accent ds-btn-lg">S'inscrire à cette formation</a>
      <a href="<?= e(whatsapp_url("Bonjour, je veux plus d'infos sur la formation : " . $f['titre'])) ?>" target="_blank" rel="noopener" class="ds-btn ds-btn-whatsapp ds-btn-lg ms-2">Poser une question sur WhatsApp</a>
    </div>
  </div>
</section>
