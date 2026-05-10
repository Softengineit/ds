<?php /** @var array $rows */ /** @var string $statut */ ?>
<h2 class="mb-4">Inscriptions</h2>

<div class="admin-card">
  <div class="d-flex flex-wrap gap-2 mb-3">
    <a href="/admin/inscriptions" class="btn btn-sm <?= $statut === '' ? 'btn-primary' : 'btn-outline-primary' ?>">Toutes</a>
    <a href="/admin/inscriptions?statut=nouveau" class="btn btn-sm <?= $statut === 'nouveau' ? 'btn-primary' : 'btn-outline-primary' ?>">Nouvelles</a>
    <a href="/admin/inscriptions?statut=contacte" class="btn btn-sm <?= $statut === 'contacte' ? 'btn-primary' : 'btn-outline-primary' ?>">Contactées</a>
    <a href="/admin/inscriptions?statut=inscrit" class="btn btn-sm <?= $statut === 'inscrit' ? 'btn-primary' : 'btn-outline-primary' ?>">Inscrites</a>
    <a href="/admin/inscriptions?statut=abandonne" class="btn btn-sm <?= $statut === 'abandonne' ? 'btn-primary' : 'btn-outline-primary' ?>">Abandons</a>
  </div>

  <?php if (empty($rows)): ?>
    <p class="text-muted">Aucune inscription.</p>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr><th>Date</th><th>Nom</th><th>Contact</th><th>Formation / Pack</th><th>Statut</th><th></th></tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td style="white-space:nowrap;font-size:0.9rem;"><?= e(date('d/m/Y H:i', strtotime($r['created_at']))) ?></td>
              <td><strong><?= e($r['nom']) ?></strong></td>
              <td>
                <a href="mailto:<?= e($r['email']) ?>"><?= e($r['email']) ?></a><br>
                <a href="https://wa.me/<?= e(preg_replace('/[^0-9]/', '', $r['telephone'])) ?>" target="_blank" style="font-size:0.9rem;"><?= e($r['telephone']) ?></a>
              </td>
              <td>
                <?= e($r['formation']) ?>
                <?php if ($r['pack']): ?><br><small class="text-muted"><?= e($r['pack']) ?></small><?php endif; ?>
              </td>
              <td><span class="table-status <?= e($r['statut']) ?>"><?= e($r['statut']) ?></span></td>
              <td><a href="/admin/inscriptions/<?= e((string)$r['id']) ?>" class="btn btn-sm btn-outline-primary">Détail</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
