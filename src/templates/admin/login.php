<?php /** @var ?string $error */ /** @var ?string $old_email */ ?>
<div class="container" style="max-width:420px;padding:4rem 1rem;">
  <div class="text-center mb-4">
    <img src="/assets/img/logo.png" alt="SEED" style="height:64px;">
    <h2 class="mt-3">Console Admin</h2>
    <p class="text-muted">SEED Digital School</p>
  </div>
  <div class="admin-card">
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>
    <form method="post" action="/admin/login">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required autofocus value="<?= e($old_email ?? '') ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Mot de passe</label>
        <input type="password" name="password" class="form-control" required minlength="10">
      </div>
      <button class="btn btn-primary w-100">Se connecter</button>
    </form>
  </div>
  <p class="text-center mt-3"><a href="/" style="color:#64748b;">← Retour au site</a></p>
</div>
