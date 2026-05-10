<?php /** @var array $content */ ?>
<header class="ds-header">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center py-3">
      <a href="<?= e(url('/')) ?>" class="ds-logo" aria-label="<?= e($content['site']['name']) ?> — Accueil">
        <img src="<?= e(asset('img/logo.png')) ?>" alt="Logo <?= e($content['site']['name']) ?>">
      </a>
      <button class="ds-burger" aria-expanded="false" aria-label="Menu">☰</button>
      <div class="ds-nav-wrapper">
        <ul class="ds-nav nav">
          <li class="nav-item"><a class="nav-link" href="<?= e(url('/')) ?>">Accueil</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(url('/formations')) ?>">Formations</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(url('/packs')) ?>">Packs</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(url('/pack-rapide')) ?>">Pack Rapide</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(url('/modules')) ?>">Modules courts</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(url('/contact')) ?>">Contact</a></li>
          <li class="nav-item"><a class="nav-link cta" href="<?= e(url('/inscription')) ?>">S'inscrire</a></li>
        </ul>
      </div>
    </div>
  </div>
</header>
