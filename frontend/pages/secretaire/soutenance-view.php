<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-calendar-check"></i> Détails de la soutenance</h1>
        <div class="page-actions">
            <?php if ($soutenance['statut'] === 'planifiee'): ?>
            <a href="/secretaire/soutenances/modifier/<?= $soutenance['id'] ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <button onclick="deleteSoutenance(<?= $soutenance['id'] ?>)" class="btn btn-danger">
                <i class="fas fa-trash"></i> Supprimer
            </button>
            <?php endif; ?>
            <a href="/secretaire/soutenances" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>
    
    <div class="admin-grid">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-info-circle"></i> Informations générales</h3>
            </div>
            <div class="card-body">
                <div class="detail-row">
                    <label>Titre</label>
                    <p><?= htmlspecialchars($soutenance['titre']) ?></p>
                </div>
                <div class="detail-row">
                    <label>Description</label>
                    <p><?= nl2br(htmlspecialchars($soutenance['description'] ?? 'Aucune description')) ?></p>
                </div>
                <div class="detail-row">
                    <label>Statut</label>
                    <p><span class="badge badge-<?= $soutenance['statut'] ?>"><?= getStatusLabel($soutenance['statut']) ?></span></p>
                </div>
                <div class="detail-row">
                    <label>Date et heure</label>
                    <p><i class="fas fa-calendar"></i> <?= formatDate($soutenance['date_heure']) ?></p>
                </div>
                <div class="detail-row">
                    <label>Durée</label>
                    <p><i class="fas fa-clock"></i> <?= $soutenance['duree'] ?> minutes</p>
                </div>
                <div class="detail-row">
                    <label>Filière</label>
                    <p><?= htmlspecialchars($soutenance['filiere']) ?></p>
                </div>
                <div class="detail-row">
                    <label>Année académique</label>
                    <p><?= htmlspecialchars($soutenance['annee_academique']) ?></p>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-user-graduate"></i> Étudiant</h3>
            </div>
            <div class="card-body">
                <div class="detail-row">
                    <label>Nom complet</label>
                    <p><?= htmlspecialchars($soutenance['etudiant_prenom'] ?? '') ?> <?= htmlspecialchars($soutenance['etudiant_nom'] ?? '') ?></p>
                </div>
                <div class="detail-row">
                    <label>Matricule</label>
                    <p><?= htmlspecialchars($soutenance['matricule'] ?? '') ?></p>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-door-open"></i> Salle</h3>
            </div>
            <div class="card-body">
                <div class="detail-row">
                    <label>Nom</label>
                    <p><?= htmlspecialchars($soutenance['salle_nom']) ?></p>
                </div>
                <div class="detail-row">
                    <label>Capacité</label>
                    <p><?= $soutenance['salle_capacite'] ?> places</p>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-users"></i> Jury</h3>
                <a href="/secretaire/soutenances/jury/<?= $soutenance['id'] ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit"></i> Gérer
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($jury)): ?>
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <p>Aucun membre du jury</p>
                </div>
                <?php else: ?>
                <?php foreach ($jury as $m): ?>
                <div class="detail-row">
                    <label><?= ucfirst($m['role']) ?></label>
                    <p>
                        <?= htmlspecialchars($m['prenom'] . ' ' . $m['nom']) ?>
                        <span class="badge badge-<?= $m['statut_confirmation'] ?>">
                            <?= getStatusLabel($m['statut_confirmation']) ?>
                        </span>
                    </p>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function deleteSoutenance(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette soutenance ?')) {
        fetch('/secretaire/soutenances/supprimer/' + id, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/secretaire/soutenances';
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(() => showToast('Erreur lors de la suppression', 'error'));
    }
}
</script>

<style>
.detail-row {
    display: flex;
    padding: 8px 0;
    border-bottom: 1px solid #f5f5f5;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-row label {
    width: 150px;
    font-weight: 600;
    color: var(--text-light);
    flex-shrink: 0;
}

.detail-row p {
    flex: 1;
    margin: 0;
}
</style>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>