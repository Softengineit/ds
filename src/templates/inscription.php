<?php
/** @var array $content */
/** @var array|null $errors */
/** @var array|null $old */
$page_title = "Inscription";
$page_description = "Inscrivez-vous à une formation SEED Digital School en quelques secondes.";

$preselected_formation = $_GET['formation'] ?? ($old['formation'] ?? '');
$preselected_pack = $_GET['pack'] ?? ($old['pack'] ?? '');

$formations = array_filter($content['formations'] ?? [], fn($f) => !empty($f['actif']));
usort($formations, fn($a, $b) => ($a['ordre'] ?? 0) <=> ($b['ordre'] ?? 0));
?>
<section class="ds-hero" style="padding:4rem 0;">
  <div class="container">
    <h1>Formulaire d'inscription</h1>
    <p class="lead">Remplissez ce formulaire en moins d'une minute. Nous reprenons contact avec vous via WhatsApp.</p>
  </div>
</section>

<section class="ds-section">
  <div class="container" style="max-width:720px;">
    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <strong>Quelques corrections nécessaires :</strong>
        <ul class="mb-0 mt-2">
          <?php foreach ($errors as $err): ?>
            <li><?= e($err) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" action="<?= e(url('/inscription')) ?>" class="ds-form" novalidate>
      <?= csrf_field() ?>
      <!-- honeypot anti-bot -->
      <div style="position:absolute;left:-9999px;" aria-hidden="true">
        <label>Site web (laissez vide) <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
      </div>

      <div class="mb-3">
        <label for="nom" class="form-label">Nom complet *</label>
        <input type="text" class="form-control" id="nom" name="nom" required minlength="2" value="<?= e($old['nom'] ?? '') ?>">
      </div>

      <div class="row g-3">
        <div class="col-md-6 mb-3">
          <label for="email" class="form-label">Email *</label>
          <input type="email" class="form-control" id="email" name="email" required value="<?= e($old['email'] ?? '') ?>">
        </div>
        <div class="col-md-6 mb-3">
          <label for="telephone" class="form-label">Téléphone *</label>
          <input type="tel" class="form-control" id="telephone" name="telephone" required pattern="\+?[0-9 ]{6,20}" placeholder="+237 6XX XX XX XX" value="<?= e($old['telephone'] ?? '') ?>">
        </div>
      </div>

      <div class="mb-3">
        <label for="formation" class="form-label">Formation choisie *</label>
        <select class="form-select" id="formation" name="formation" required>
          <option value="">— Choisissez une formation —</option>
          <?php foreach ($formations as $f): ?>
            <option value="<?= e($f['titre']) ?>" <?= $preselected_formation === $f['titre'] ? 'selected' : '' ?>><?= e($f['titre']) ?></option>
          <?php endforeach; ?>
          <?php if (!empty($content['cursus_complet']['actif'])): ?>
            <option value="<?= e($content['cursus_complet']['titre']) ?>" <?= $preselected_formation === $content['cursus_complet']['titre'] ? 'selected' : '' ?>><?= e($content['cursus_complet']['titre']) ?></option>
          <?php endif; ?>
          <option value="<?= e($content['pack_rapide']['nom']) ?>" <?= $preselected_formation === $content['pack_rapide']['nom'] ? 'selected' : '' ?>><?= e($content['pack_rapide']['nom']) ?></option>
          <option value="Modules courts">Modules courts (Word, Excel, PowerPoint, Photoshop, etc.)</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="pack" class="form-label">Pack souhaité</label>
        <select class="form-select" id="pack" name="pack">
          <option value="">— À déterminer —</option>
          <?php foreach ($content['packs_principaux'] as $pack): ?>
            <option value="<?= e($pack['nom']) ?>" <?= $preselected_pack === $pack['nom'] ? 'selected' : '' ?>>
              <?= e($pack['nom']) ?> — <?= e($pack['duree_label']) ?> (<?= e(format_fcfa($pack['prix_unique_fcfa'])) ?>)
            </option>
          <?php endforeach; ?>
          <option value="<?= e($content['pack_rapide']['nom']) ?>" <?= $preselected_pack === $content['pack_rapide']['nom'] ? 'selected' : '' ?>>
            <?= e($content['pack_rapide']['nom']) ?> (<?= e(format_fcfa($content['pack_rapide']['prix_fcfa'])) ?>)
          </option>
        </select>
      </div>

      <div class="mb-3">
        <label for="groupe" class="form-label">Groupe préféré</label>
        <select class="form-select" id="groupe" name="groupe">
          <option value="">— Indifférent —</option>
          <?php foreach ($content['groupes'] as $g): ?>
            <option value="<?= e($g['label']) ?>" <?= ($old['groupe'] ?? '') === $g['label'] ? 'selected' : '' ?>><?= e($g['label']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label for="message" class="form-label">Message (facultatif)</label>
        <textarea class="form-control" id="message" name="message" rows="3" placeholder="Une question ? Une précision ?"><?= e($old['message'] ?? '') ?></textarea>
      </div>

      <div class="d-flex flex-column flex-md-row gap-2 mt-4">
        <button type="submit" class="ds-btn ds-btn-whatsapp ds-btn-lg">Envoyer mon inscription via WhatsApp</button>
        <a href="<?= e(whatsapp_url()) ?>" target="_blank" rel="noopener" class="ds-btn ds-btn-outline ds-btn-lg">Préférer discuter d'abord</a>
      </div>
      <p class="text-muted mt-3" style="font-size:0.9rem;">
        En soumettant ce formulaire, vous acceptez d'être recontacté(e) par <?= e($content['site']['name']) ?>. Vos données sont conservées de manière sécurisée et ne sont jamais partagées.
      </p>
    </form>
  </div>
</section>
