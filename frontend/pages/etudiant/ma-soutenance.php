<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-user-graduate"></i> Ma soutenance</h1>
    </div>
    
    <?php if ($soutenance): ?>
    <div class="card">
        <div class="card-header">
            <h3><?= htmlspecialchars($soutenance['titre']) ?></h3>
            <span class="badge badge-<?= $soutenance['statut'] ?>"><?= getStatusLabel($soutenance['statut']) ?></span>
        </div>
        <div class="card-body">
            <div class="detail-row">
                <label>Étudiant :</label>
                <p><?= htmlspecialchars($_SESSION['user_name']) ?></p>
            </div>
            <div class="detail-row">
                <label>Date :</label>
                <p><?= formatDate($soutenance['date_heure']) ?></p>
            </div>
            <div class="detail-row">
                <label>Durée :</label>
                <p><?= $soutenance['duree'] ?> minutes</p>
            </div>
            <div class="detail-row">
                <label>Salle :</label>
                <p><?= htmlspecialchars($soutenance['salle_nom']) ?></p>
            </div>
            <div class="detail-row">
                <label>Filière :</label>
                <p><?= htmlspecialchars($soutenance['filiere']) ?></p>
            </div>
            <div class="detail-row">
                <label>Description :</label>
                <p><?= nl2br(htmlspecialchars($soutenance['description'] ?? 'Aucune description')) ?></p>
            </div>
            
            <h4 style="margin-top: 20px;"><i class="fas fa-users"></i> Jury</h4>
            <?php if (isset($jury) && $jury): ?>
            <?php foreach ($jury as $membre): ?>
            <div class="detail-row">
                <label><?= ucfirst($membre['role']) ?> :</label>
                <p><?= htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']) ?></p>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <p class="text-muted">Jury en cours de composition.</p>
            <?php endif; ?>
            
            <?php if ($soutenance['statut'] === 'terminee' && isset($pv) && $pv['statut'] === 'valide'): ?>
            <h4 style="margin-top: 20px;"><i class="fas fa-chart-bar"></i> Résultats</h4>
            <div class="result-box">
                <div class="detail-row">
                    <label>Note :</label>
                    <p><strong style="font-size: 20px; color: var(--primary-color);"><?= number_format($pv['note'], 2) ?>/20</strong></p>
                </div>
                <div class="detail-row">
                    <label>Mention :</label>
                    <p><span class="badge badge-<?= $pv['mention'] ?>" style="font-size: 16px; padding: 5px 15px;"><?= getMentionLabel($pv['mention']) ?></span></p>
                </div>
                <?php if ($pv['commentaire']): ?>
                <div class="detail-row">
                    <label>Commentaire :</label>
                    <p><?= nl2br(htmlspecialchars($pv['commentaire'])) ?></p>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="page-actions" style="margin-top: 20px;">
        <?php if ($soutenance['statut'] === 'planifiee'): ?>
        <a href="/etudiant/convocation/<?= $soutenance['id'] ?>" class="btn btn-primary">
            <i class="fas fa-file-pdf"></i> Télécharger la convocation
        </a>
        <?php endif; ?>
        <?php if ($soutenance['statut'] === 'terminee' && isset($pv) && $pv['statut'] === 'valide'): ?>
        <a href="/etudiant/telecharger-pv/<?= $soutenance['id'] ?>" class="btn btn-success">
            <i class="fas fa-file-pdf"></i> Télécharger le PV
        </a>
        <a href="/etudiant/attestation/<?= $soutenance['id'] ?>" class="btn btn-info">
            <i class="fas fa-certificate"></i> Attestation
        </a>
        <?php endif; ?>
    </div>
    
    <?php else: ?>
    <div class="card">
        <div class="empty-state">
            <i class="fas fa-calendar-plus" style="font-size: 64px;"></i>
            <h4>Aucune soutenance</h4>
            <p>Vous n'avez pas encore de soutenance planifiée.</p>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.detail-row {
    display: flex;
    padding: 6px 0;
    border-bottom: 1px solid #f5f5f5;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-row label {
    width: 140px;
    font-weight: 600;
    color: var(--text-light);
    flex-shrink: 0;
}

.detail-row p {
    flex: 1;
    margin: 0;
}

.result-box {
    background: #f8f9fa;
    padding: 15px;
    border-radius: var(--border-radius);
    margin-top: 10px;
}
</style>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>