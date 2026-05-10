<?php
/** @var bool|null $ok */
/** @var string|null $error */
/** @var string|null $raw */
$current = $raw ?? json_encode(load_content(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
<h2 class="mb-4">Contenu du site</h2>

<?php if (!empty($ok)): ?>
  <div class="alert alert-success">✅ Contenu mis à jour. Une copie de sauvegarde a été automatiquement créée.</div>
<?php endif; ?>
<?php if (!empty($error)): ?>
  <div class="alert alert-danger">❌ <?= e($error) ?></div>
<?php endif; ?>

<div class="admin-card">
  <p class="text-muted">
    Édition directe du fichier <code>content.json</code>. Toute modification crée automatiquement une sauvegarde dans
    <code>data/content.backup.YYYYMMDD-HHMMSS.json</code>.
    <br><strong>Attention :</strong> le JSON doit rester valide. Une accolade ou virgule mal placée bloque le site.
  </p>
  <form method="post">
    <?= csrf_field() ?>
    <textarea name="content_json" rows="32" class="form-control" style="font-family:Consolas,'Courier New',monospace;font-size:0.85rem;"><?= e($current) ?></textarea>
    <div class="mt-3 d-flex gap-2">
      <button class="btn btn-primary">💾 Enregistrer</button>
      <a href="/admin/contenu" class="btn btn-outline-secondary">Annuler</a>
      <a href="/" target="_blank" class="btn btn-link">🔍 Voir le site</a>
    </div>
  </form>
</div>

<div class="admin-card">
  <h5>Astuce</h5>
  <p class="text-muted small mb-0">Pour les modifications structurelles (ajouter une formation, créer un nouveau pack), demande à un administrateur technique. Pour les ajustements simples (prix, textes, contacts, dates), tu peux modifier directement les valeurs ci-dessus.</p>
</div>
