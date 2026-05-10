<?php
/** @var array $content */
$page_title = "Contact";
$page_description = "Contactez SEED Digital School à Yaoundé. WhatsApp, téléphone, email ou formulaire.";
?>
<section class="ds-hero" style="padding:4rem 0;">
  <div class="container">
    <h1>Contactez-nous</h1>
    <p class="lead">Une question, une demande de devis, un partenariat ? Nous vous répondons rapidement.</p>
  </div>
</section>

<section class="ds-section">
  <div class="container">
    <div class="row g-5">
      <div class="col-md-5">
        <h3>Nos coordonnées</h3>
        <p style="line-height:1.9;">
          <strong>📍 Adresse :</strong><br>
          <?php foreach ($content['contact']['adresse_lignes'] as $l): ?>
            <?= e($l) ?><br>
          <?php endforeach; ?>
        </p>
        <p>
          <strong>📞 Téléphone :</strong><br>
          <a href="tel:+<?= e($content['contact']['phone']) ?>"><?= e($content['contact']['phone_display']) ?></a><br>
          <a href="tel:+<?= e($content['contact']['whatsapp']) ?>"><?= e($content['contact']['whatsapp_display']) ?></a> (WhatsApp)
        </p>
        <p>
          <strong>✉️ Email :</strong><br>
          <a href="mailto:<?= e($content['contact']['email']) ?>"><?= e($content['contact']['email']) ?></a>
        </p>
        <div class="mt-4">
          <a href="<?= e(whatsapp_url('Bonjour, j\'ai une question.')) ?>" target="_blank" rel="noopener" class="ds-btn ds-btn-whatsapp">
            💬 Discuter sur WhatsApp
          </a>
        </div>
      </div>

      <div class="col-md-7">
        <h3>Formulaire de contact</h3>
        <form id="contactForm" class="ds-form" onsubmit="redirectContactToWhatsApp(event)">
          <div class="mb-3">
            <label for="cname" class="form-label">Nom *</label>
            <input type="text" class="form-control" id="cname" required>
          </div>
          <div class="mb-3">
            <label for="cemail" class="form-label">Email *</label>
            <input type="email" class="form-control" id="cemail" required>
          </div>
          <div class="mb-3">
            <label for="cmessage" class="form-label">Message *</label>
            <textarea class="form-control" id="cmessage" rows="4" required></textarea>
          </div>
          <button type="submit" class="ds-btn ds-btn-primary ds-btn-lg">Envoyer</button>
        </form>
        <script>
          function redirectContactToWhatsApp(e) {
            e.preventDefault();
            const name = document.getElementById('cname').value;
            const email = document.getElementById('cemail').value;
            const msg = document.getElementById('cmessage').value;
            const text = encodeURIComponent(`Bonjour, je suis ${name} (${email}).\n\n${msg}`);
            window.open(`https://wa.me/<?= e($content['contact']['whatsapp']) ?>?text=${text}`, '_blank');
          }
        </script>
      </div>
    </div>
  </div>
</section>
