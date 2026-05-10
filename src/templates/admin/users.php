<?php /** @var array $rows */ /** @var array $me */ ?>
<h2 class="mb-4">Utilisateurs</h2>

<div class="admin-card">
  <h5>Créer un utilisateur</h5>
  <form method="post" class="row g-2 align-items-end">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="create">
    <div class="col-md-3">
      <label class="form-label">Nom</label>
      <input name="nom" class="form-control" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="col-md-2">
      <label class="form-label">Rôle</label>
      <select name="role" class="form-select">
        <option value="editor">Éditeur</option>
        <option value="admin">Admin</option>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">Mot de passe (10+ chars)</label>
      <input type="text" name="password" class="form-control" required minlength="10">
    </div>
    <div class="col-md-1">
      <button class="btn btn-primary w-100">Créer</button>
    </div>
  </form>
</div>

<div class="admin-card">
  <h5 class="mb-3">Comptes existants</h5>
  <table class="table align-middle">
    <thead><tr><th>Nom</th><th>Email</th><th>Rôle</th><th>Dernière connexion</th><th>Créé</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($rows as $u): ?>
        <tr>
          <td><strong><?= e($u['nom']) ?></strong> <?= (int)$u['id'] === (int)$me['id'] ? '<span class="badge bg-secondary ms-1">moi</span>' : '' ?></td>
          <td><?= e($u['email']) ?></td>
          <td><span class="badge bg-<?= $u['role'] === 'admin' ? 'danger' : 'primary' ?>"><?= e($u['role']) ?></span></td>
          <td><?= $u['last_login_at'] ? e(date('d/m/Y H:i', strtotime($u['last_login_at']))) : '<em>jamais</em>' ?></td>
          <td><?= e(date('d/m/Y', strtotime($u['created_at']))) ?></td>
          <td>
            <?php if ((int)$u['id'] !== (int)$me['id']): ?>
              <form method="post" class="d-inline" onsubmit="return confirm('Supprimer ce compte ?');">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= e((string)$u['id']) ?>">
                <button class="btn btn-sm btn-outline-danger">Supprimer</button>
              </form>
              <button class="btn btn-sm btn-outline-warning" onclick="resetPwd(<?= (int)$u['id'] ?>)">Réinitialiser MDP</button>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<form id="resetForm" method="post" style="display:none;">
  <?= csrf_field() ?>
  <input type="hidden" name="action" value="reset_password">
  <input type="hidden" name="id" id="resetId">
  <input type="hidden" name="new_password" id="resetPwd">
</form>
<script>
  function resetPwd(id) {
    const p = prompt('Nouveau mot de passe pour cet utilisateur (10 caractères minimum) :');
    if (!p || p.length < 10) return alert('Mot de passe trop court.');
    document.getElementById('resetId').value = id;
    document.getElementById('resetPwd').value = p;
    document.getElementById('resetForm').submit();
  }
</script>
