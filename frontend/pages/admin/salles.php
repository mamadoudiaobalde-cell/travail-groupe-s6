<?php
require_once __DIR__ . '/../../../includes/bootstrap.php';
require_once __DIR__ . '/../../../includes/models/Salle.php';
requireRole(['administrateur']);

$search = trim($_GET['q'] ?? '');
$salles = Salle::all($search !== '' ? $search : null);
$pageTitle = 'Gestion des salles';
require_once __DIR__ . '/../../../includes/header.php';
?>
<div class="page-header">
    <div><h1>Gestion des salles</h1><p class="lead"><?= count($salles) ?> salle(s)</p></div>
    <a href="create.php" class="btn btn-primary">+ Nouvelle salle</a>
</div>
<form method="GET" class="search-bar">
    <input type="text" name="q" value="<?= e($search) ?>" placeholder="Rechercher...">
    <button type="submit" class="btn btn-secondary">Rechercher</button>
    <?php if ($search): ?><a href="index.php" class="btn btn-outline-dark">Réinitialiser</a><?php endif; ?>
</form>
<?php if (empty($salles)): ?>
<div class="empty-state"><p>Aucune salle trouvée.</p><a href="create.php" class="btn btn-primary">Créer une salle</a></div>
<?php else: ?>
<div class="table-wrapper">
<table class="table">
<thead><tr><th>Nom</th><th>Capacité</th><th>Localisation</th><th>Équipements</th><th>Statut</th><th>Actions</th></tr></thead>
<tbody>
<?php foreach ($salles as $s): ?>
<tr>
    <td><strong><?= e($s['nom']) ?></strong></td>
    <td><?= (int)$s['capacite'] ?> places</td>
    <td><?= e($s['localisation'] ?: '—') ?></td>
    <td class="text-truncate"><?= e($s['equipements'] ?: '—') ?></td>
    <td><span class="badge badge-<?= $s['actif'] ? 'active' : 'inactive' ?>"><?= $s['actif'] ? 'Active' : 'Inactive' ?></span></td>
    <td class="col-actions">
        <a href="edit.php?id=<?= (int)$s['id'] ?>" class="btn btn-sm btn-secondary">Modifier</a>
        <form method="POST" action="delete.php" class="inline-form" onsubmit="return confirm('Supprimer cette salle ?');">
            <?= csrfField() ?><input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</tbody></table></div>
<?php endif; ?>
<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
