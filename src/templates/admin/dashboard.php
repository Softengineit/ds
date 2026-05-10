<?php /** @var array $stats */ /** @var array $me */ ?>
<h2 class="mb-4">Tableau de bord</h2>

<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="admin-stat">
      <div class="admin-stat-label">Inscriptions totales</div>
      <div class="admin-stat-value"><?= e((string)$stats['total_inscriptions']) ?></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="admin-stat" style="border-top-color:#f59e0b;">
      <div class="admin-stat-label">Nouvelles à traiter</div>
      <div class="admin-stat-value"><?= e((string)$stats['nouveaux']) ?></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="admin-stat" style="border-top-color:#198754;">
      <div class="admin-stat-label">Connexion</div>
      <div class="admin-stat-value" style="font-size:1rem;font-weight:600;"><?= e($me['email']) ?></div>
    </div>
  </div>
</div>

<div class="admin-card">
  <h4 class="mb-3">Dernières inscriptions</h4>
  <?php if (empty($stats['derniers'])): ?>
    <p class="text-muted">Aucune inscription pour l'instant.</p>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr><th>Date</th><th>Nom</th><th>Email</th><th>Formation</th><th>Statut</th><th></th></tr>
        </thead>
        <tbody>
          <?php foreach ($stats['derniers'] as $r): ?>
            <tr>
              <td><?= e(date('d/m H:i', strtotime($r['created_at']))) ?></td>
              <td><?= e($r['nom']) ?></td>
              <td><?= e($r['email']) ?></td>
              <td><?= e($r['formation']) ?></td>
              <td><span class="table-status <?= e($r['statut']) ?>"><?= e($r['statut']) ?></span></td>
              <td><a href="/admin/inscriptions/<?= e((string)$r['id']) ?>" class="btn btn-sm btn-outline-primary">Voir</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
  <div class="mt-3"><a href="/admin/inscriptions" class="btn btn-link">Toutes les inscriptions →</a></div>
</div>
