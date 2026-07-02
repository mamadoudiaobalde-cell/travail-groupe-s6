<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-check-double"></i> Confirmations jury</h1>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-info-circle"></i> Instructions</h3>
        </div>
        <div class="card-body">
            <p>Vous trouverez ci-dessous la liste des soutenances pour lesquelles vous avez été invité à participer au jury.</p>
            <p>Veuillez confirmer ou refuser votre participation pour permettre l'organisation des soutenances.</p>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <span>Les invitations en attente de confirmation seront automatiquement relancées après 48h.</span>
            </div>
        </div>
    </div>
    
    <div class="table-container">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Soutenance</th>
                        <th>Étudiant</th>
                        <th>Date</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($invitations)): ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            <i class="fas fa-check-double"></i>
                            <h4>Aucune invitation en attente</h4>
                            <p>Vous avez déjà confirmé toutes vos participations.</p>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($invitations as $inv): ?>
                    <tr>
                        <td><?= htmlspecialchars($inv['titre']) ?></td>
                        <td><?= htmlspecialchars($inv['etudiant_nom'] ?? '') ?> <?= htmlspecialchars($inv['etudiant_prenom'] ?? '') ?></td>
                        <td><?= formatDate($inv['date_heure']) ?></td>
                        <td><span class="badge badge-<?= $inv['role'] ?>"><?= ucfirst($inv['role']) ?></span></td>
                        <td>
                            <?php if ($inv['statut_confirmation'] === 'en_attente'): ?>
                            <span class="badge badge-warning">En attente</span>
                            <?php else: ?>
                            <span class="badge badge-<?= $inv['statut_confirmation'] ?>"><?= ucfirst($inv['statut_confirmation']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($inv['statut_confirmation'] === 'en_attente'): ?>
                            <button onclick="confirmerInvitation(<?= $inv['id'] ?>, 'confirme')" class="btn btn-sm btn-success">
                                <i class="fas fa-check"></i> Confirmer
                            </button>
                            <button onclick="confirmerInvitation(<?= $inv['id'] ?>, 'refuse')" class="btn btn-sm btn-danger">
                                <i class="fas fa-times"></i> Refuser
                            </button>
                            <?php endif; ?>
                            <a href="/enseignant/soutenances/voir/<?= $inv['id'] ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
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
function confirmerInvitation(soutenanceId, statut) {
    const message = statut === 'confirme' ? 'Confirmer votre participation au jury ?' : 'Refuser votre participation au jury ?';
    if (!confirm(message)) return;
    
    fetch('/enseignant/jury/confirmer/' + soutenanceId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ statut: statut })
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
    .catch(() => showToast('Erreur lors de la confirmation', 'error'));
}
</script>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>