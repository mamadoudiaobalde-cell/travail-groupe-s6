<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-graduation-cap"></i> Gestion des promotions</h1>
    </div>
    
    <div class="filters">
        <form method="GET" action="/responsable/promotions" class="filter-form">
            <div class="filter-group">
                <label>Année académique</label>
                <select name="annee">
                    <option value="">Toutes</option>
                    <?php foreach ($annees as $a): ?>
                    <option value="<?= $a ?>" <?= ($_GET['annee'] ?? '') === $a ? 'selected' : '' ?>>
                        <?= $a ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrer</button>
        </form>
    </div>
    
    <div class="table-container">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Matricule</th>
                        <th>Étudiant</th>
                        <th>Filière</th>
                        <th>Année</th>
                        <th>Soutenances</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($promotions)): ?>
                    <tr>
                        <td colspan="7" class="empty-state">
                            <i class="fas fa-users"></i>
                            <h4>Aucun étudiant</h4>
                            <p>Aucun étudiant inscrit pour cette année académique.</p>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($promotions as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['matricule'] ?? '') ?></td>
                        <td><?= htmlspecialchars($p['prenom'] . ' ' . $p['nom']) ?></td>
                        <td><?= htmlspecialchars($p['specialite'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($p['annee_academique'] ?? '-') ?></td>
                        <td>
                            <?php if ($p['nb_soutenances'] ?? 0 > 0): ?>
                            <span class="badge badge-info"><?= $p['nb_soutenances'] ?></span>
                            <?php else: ?>
                            <span class="badge badge-secondary">0</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($p['a_soutenu'] ?? false): ?>
                            <span class="badge badge-success">A soutenu</span>
                            <?php else: ?>
                            <span class="badge badge-warning">En attente</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/responsable/etudiant/<?= $p['id'] ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php if (!($p['a_soutenu'] ?? false)): ?>
                            <button onclick="relancerEtudiant(<?= $p['id'] ?>)" class="btn btn-sm btn-warning">
                                <i class="fas fa-envelope"></i>
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function relancerEtudiant(id) {
    if (!confirm('Envoyer un email de relance à cet étudiant ?')) return;
    
    fetch('/responsable/promotions/relancer/' + id, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(() => showToast('Erreur lors de l\'envoi', 'error'));
}
</script>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>