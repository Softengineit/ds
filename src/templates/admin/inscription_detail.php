<?php /** @var array $row */ ?>
<h2 class="mb-4">Inscription #<?= e((string)$row['id']) ?></h2>

<div class="row g-3">
  <div class="col-md-7">
    <div class="admin-card">
      <h4 class="mb-3"><?= e($row['nom']) ?></h4>
      <dl class="row">
        <dt class="col-sm-4">Email</dt>
        <dd class="col-sm-8"><a href="mailto:<?= e($row['email']) ?>"><?= e($row['email']) ?></a></dd>

        <dt class="col-sm-4">Téléphone</dt>
        <dd class="col-sm-8">
          <a href="https://wa.me/<?= e(preg_replace('/[^0-9]/', '', $row['telephone'])) ?>" target="_blank">
            💬 <?= e($row['telephone']) ?> (WhatsApp)
          </a>
        </dd>

        <dt class="col-sm-4">Formation</dt>
        <dd class="col-sm-8"><?= e($row['formation']) ?></dd>

        <?php if ($row['pack']): ?>
          <dt class="col-sm-4">Pack</dt>
          <dd class="col-sm-8"><?= e($row['pack']) ?></dd>
        <?php endif; ?>

        <?php if ($row['groupe']): ?>
          <dt class="col-sm-4">Groupe</dt>
          <dd class="col-sm-8"><?= e($row['groupe']) ?></dd>
        <?php endif; ?>

        <?php if ($row['message']): ?>
          <dt class="col-sm-4">Message</dt>
          <dd class="col-sm-8" style="white-space:pre-wrap;"><?= e($row['message']) ?></dd>
        <?php endif; ?>

        <dt class="col-sm-4">Reçu le</dt>
        <dd class="col-sm-8"><?= e(date('d/m/Y à H:i', strtotime($row['created_at']))) ?></dd>

        <dt class="col-sm-4">IP</dt>
        <dd class="col-sm-8"><code><?= e($row['ip']) ?></code></dd>
      </dl>
    </div>
  </div>

  <div class="col-md-5">
    <div class="admin-card">
      <h5>Mettre à jour</h5>
      <form method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label">Statut</label>
          <select name="statut" class="form-select">
            <?php foreach (['nouveau','contacte','inscrit','abandonne'] as $s): ?>
              <option value="<?= e($s) ?>" <?= $row['statut'] === $s ? 'selected' : '' ?>><?= e($s) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Notes internes</label>
          <textarea name="notes_internes" rows="4" class="form-control"><?= e($row['notes_internes'] ?? '') ?></textarea>
        </div>
        <button class="btn btn-primary">Enregistrer</button>
        <a href="/admin/inscriptions" class="btn btn-link">← Retour</a>
      </form>
    </div>
  </div>
</div>
