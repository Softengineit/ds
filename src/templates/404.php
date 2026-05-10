<?php
$page_title = "Page introuvable";
$page_description = "La page que vous cherchez n'existe pas ou a été déplacée.";
?>
<section class="ds-section text-center" style="padding:6rem 0;">
  <div class="container">
    <h1 style="font-size:6rem;color:var(--ds-primary);margin-bottom:0;">404</h1>
    <h2>Page introuvable</h2>
    <p class="lead text-muted">La page que vous cherchez n'existe pas ou a été déplacée.</p>
    <div class="mt-4">
      <a href="<?= e(url('/')) ?>" class="ds-btn ds-btn-primary ds-btn-lg">← Retour à l'accueil</a>
    </div>
  </div>
</section>
