<?php /** @var array $me */ /** @var bool|null $ok */ /** @var string|null $error */ ?>
<h2 class="mb-4">Mon compte</h2>

<div class="admin-card">
  <dl class="row">
    <dt class="col-sm-3">Nom</dt><dd class="col-sm-9"><?= e($me['nom']) ?></dd>
    <dt class="col-sm-3">Email</dt><dd class="col-sm-9"><?= e($me['email']) ?></dd>
    <dt class="col-sm-3">Rôle</dt><dd class="col-sm-9"><span class="badge bg-<?= $me['role'] === 'admin' ? 'danger' : 'primary' ?>"><?= e($me['role']) ?></span></dd>
    <dt class="col-sm-3">Compte créé</dt><dd class="col-sm-9"><?= e(date('d/m/Y', strtotime($me['created_at']))) ?></dd>
  </dl>
</div>

<div class="admin-card">
  <h5>Changer mon mot de passe</h5>
  <?php if (!empty($ok)): ?><div class="alert alert-success">✅ Mot de passe mis à jour.</div><?php endif; ?>
  <?php if (!empty($error)): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
  <form method="post" style="max-width:480px;">
    <?= csrf_field() ?>
    <div class="mb-3">
      <label class="form-label">Mot de passe actuel</label>
      <input type="password" name="current_password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Nouveau mot de passe (10 chars min)</label>
      <input type="password" name="new_password" class="form-control" required minlength="10">
    </div>
    <button class="btn btn-primary">Mettre à jour</button>
  </form>
</div>
