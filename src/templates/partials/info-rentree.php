<?php
/** @var array $content */
$ir = $content['info_rentree'] ?? null;
if (!$ir || empty($ir['active'])) return;
$dateLabel = !empty($ir['date_rentree_auto']) ? date_rentree_auto() : ($ir['date_rentree_manuelle'] ?? '');
$style = '';
if (!empty($ir['couleur_fond'])) $style .= '--info-rentree-bg:' . htmlspecialchars($ir['couleur_fond'], ENT_QUOTES) . ';';
if (!empty($ir['couleur_texte'])) $style .= '--info-rentree-color:' . htmlspecialchars($ir['couleur_texte'], ENT_QUOTES) . ';';
?>
<div class="ds-info-rentree" style="<?= $style ?>">
  <strong><?= e($ir['titre']) ?> :</strong> <?= e($ir['message']) ?>
  <?php if ($dateLabel): ?>
    — <strong><?= e($dateLabel) ?></strong>
  <?php endif; ?>
  <button class="close-btn" aria-label="Fermer">&times;</button>
</div>
