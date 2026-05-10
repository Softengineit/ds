<?php /** @var array $content */ ?>
<footer class="ds-footer">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4">
        <img src="<?= e(asset('img/logo.png')) ?>" alt="<?= e($content['site']['name']) ?>" style="height:48px;margin-bottom:1rem;">
        <p style="opacity:0.8;"><?= e($content['site']['slogan']) ?></p>
        <?php if (array_filter($content['social'])): ?>
          <div class="ds-social mt-3">
            <?php foreach ($content['social'] as $key => $url): if ($url): ?>
              <a href="<?= e($url) ?>" target="_blank" rel="noopener" aria-label="<?= e(ucfirst($key)) ?>"><?= strtoupper(substr($key, 0, 1)) ?></a>
            <?php endif; endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      <div class="col-md-3 col-6">
        <h5>Navigation</h5>
        <ul class="list-unstyled" style="line-height:2.2;">
          <li><a href="<?= e(url('/formations')) ?>">Nos formations</a></li>
          <li><a href="<?= e(url('/packs')) ?>">Packs longs</a></li>
          <li><a href="<?= e(url('/pack-rapide')) ?>">Pack Rapide</a></li>
          <li><a href="<?= e(url('/modules')) ?>">Modules courts</a></li>
          <li><a href="<?= e(url('/inscription')) ?>">S'inscrire</a></li>
        </ul>
      </div>
      <div class="col-md-5 col-6">
        <h5>Contact</h5>
        <p style="line-height:1.8;">
          <?php foreach ($content['contact']['adresse_lignes'] as $l): ?>
            <?= e($l) ?><br>
          <?php endforeach; ?>
        </p>
        <p>
          📞 <a href="tel:+<?= e($content['contact']['phone']) ?>"><?= e($content['contact']['phone_display']) ?></a><br>
          📞 <a href="tel:+<?= e($content['contact']['whatsapp']) ?>"><?= e($content['contact']['whatsapp_display']) ?></a><br>
          ✉️ <a href="mailto:<?= e($content['contact']['email']) ?>"><?= e($content['contact']['email']) ?></a>
        </p>
      </div>
    </div>
    <div class="ds-footer-bottom">
      &copy; <span data-current-year><?= e((string)$content['site']['annee_courante']) ?></span>
      <?= e($content['site']['name']) ?>. <?= e($content['footer']['texte_droits']) ?>
    </div>
  </div>
</footer>
