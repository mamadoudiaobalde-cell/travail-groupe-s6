<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-envelope"></i> Gestion des convocations</h1>
        <div class="page-actions">
            <button onclick="genererConvocations()" class="btn btn-primary">
                <i class="fas fa-file-pdf"></i> Générer toutes les convocations
            </button>
        </div>
    </div>
    
    <div class="filters">
        <form method="GET" action="/secretaire/convocations" class="filter-form">
            <div class="filter-group">
                <label>Date</label>
                <input type="date" name="date" value="<?= $_GET['date'] ?? '' ?>">
            </div>
            <div class="filter-group">
                <label>Filière</label>
                <select name="filiere">
                    <option value="">Toutes</option>
                    <?php foreach ($filieres as $f): ?>
                    <option value="<?= $f ?>" <?= ($_GET['filiere'] ?? '') === $f ? 'selected' : '' ?>>
                        <?= $f ?>
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
                        <th>Étudiant</th>
                        <th>Soutenance</th>
                        <th>Date</th>
                        <th>Salle</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($soutenances)): ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            <i class="fas fa-envelope"></i>
                            <h4>Aucune convocation à générer</h4>
                            <p>Aucune soutenance planifiée pour cette période.</p>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($soutenances as $s): ?>
                    <tr>
                        <td><?= htmlspecialchars($s['etudiant_prenom'] ?? '') ?> <?= htmlspecialchars($s['etudiant_nom'] ?? '') ?></td>
                        <td><?= htmlspecialchars($s['titre']) ?></td>
                        <td><?= formatDate($s['date_heure']) ?></td>
                        <td><?= htmlspecialchars($s['salle_nom']) ?></td>
                        <td>
                            <?php if ($s['convocation_envoyee'] ?? false): ?>
                            <span class="badge badge-success">Envoyée</span>
                            <?php else: ?>
                            <span class="badge badge-warning">En attente</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button onclick="envoyerConvocation(<?= $s['id'] ?>)" class="btn btn-sm btn-primary">
                                <i class="fas fa-paper-plane"></i> Envoyer
                            </button>
                            <button onclick="telechargerConvocation(<?= $s['id'] ?>)" class="btn btn-sm btn-secondary">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
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
function envoyerConvocation(id) {
    if (confirm('Envoyer la convocation par email ?')) {
        fetch('/secretaire/convocations/envoyer/' + id, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                location.reload();
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(() => showToast('Erreur lors de l\'envoi', 'error'));
    }
}

function telechargerConvocation(id) {
    window.location.href = '/secretaire/convocations/telecharger/' + id;
}

function genererConvocations() {
    if (confirm('Générer toutes les convocations en PDF ?')) {
        window.location.href = '/secretaire/convocations/generer-toutes';
    }
}
</script>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>