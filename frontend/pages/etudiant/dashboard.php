<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="dashboard">
    <div class="dashboard-welcome">
        <h2>Bonjour <?= htmlspecialchars($_SESSION['user_name']) ?> 🎓</h2>
        <p>Bienvenue sur votre espace étudiant. Suivez l'état de votre soutenance.</p>
    </div>
    
    <?php if ($soutenance): ?>
    <div class="dashboard-stats">
        <?php include_component('cards/stat-card', [
            'value' => formatDate($soutenance['date_heure'], 'd/m/Y'),
            'label' => 'Date de soutenance',
            'icon' => 'fa-calendar-day',
            'iconClass' => 'blue'
        ]); ?>
        <?php include_component('cards/stat-card', [
            'value' => $soutenance['duree'] . ' min',
            'label' => 'Durée',
            'icon' => 'fa-clock',
            'iconClass' => 'orange'
        ]); ?>
        <?php include_component('cards/stat-card', [
            'value' => htmlspecialchars($soutenance['salle_nom']),
            'label' => 'Salle',
            'icon' => 'fa-door-open',
            'iconClass' => 'green'
        ]); ?>
        <?php include_component('cards/stat-card', [
            'value' => '<span class="badge badge-' . $soutenance['statut'] . '">' . getStatusLabel($soutenance['statut']) . '</span>',
            'label' => 'Statut',
            'icon' => 'fa-info-circle',
            'iconClass' => 'purple'
        ]); ?>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-info-circle"></i> Détails de la soutenance</h3>
            <?php if ($soutenance['statut'] === 'terminee' && isset($pv) && $pv['statut'] === 'valide'): ?>
            <a href="/etudiant/telecharger-pv/<?= $soutenance['id'] ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-file-pdf"></i> Télécharger le PV
            </a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="detail-row">
                <label>Titre :</label>
                <p><?= htmlspecialchars($soutenance['titre']) ?></p>
            </div>
            <div class="detail-row">
                <label>Description :</label>
                <p><?= nl2br(htmlspecialchars($soutenance['description'] ?? 'Aucune description')) ?></p>
            </div>
            <div class="detail-row">
                <label>Filière :</label>
                <p><?= htmlspecialchars($soutenance['filiere']) ?></p>
            </div>
            <div class="detail-row">
                <label>Année académique :</label>
                <p><?= htmlspecialchars($soutenance['annee_academique']) ?></p>
            </div>
            
            <?php if (isset($jury) && $jury): ?>
            <h4 style="margin-top: 15px;"><i class="fas fa-users"></i> Jury</h4>
            <?php foreach ($jury as $membre): ?>
            <div class="detail-row">
                <label><?= ucfirst($membre['role']) ?> :</label>
                <p>
                    <?= htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']) ?>
                    <span class="badge badge-<?= $membre['statut_confirmation'] ?>">
                        <?= getStatusLabel($membre['statut_confirmation']) ?>
                    </span>
                </p>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
            
            <?php if (isset($pv) && $pv['statut'] === 'valide'): ?>
            <h4 style="margin-top: 15px;"><i class="fas fa-chart-bar"></i> Résultats</h4>
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
    
    <div class="admin-grid">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-bolt"></i> Actions rapides</h3>
            </div>
            <div class="card-body">
                <div class="quick-actions">
                    <a href="/etudiant/resultats" class="quick-action">
                        <i class="fas fa-chart-bar"></i>
                        <span>Voir mes résultats</span>
                    </a>
                    <a href="/etudiant/historique" class="quick-action">
                        <i class="fas fa-history"></i>
                        <span>Historique</span>
                    </a>
                    <?php if ($soutenance['statut'] === 'terminee' && isset($pv) && $pv['statut'] === 'valide'): ?>
                    <a href="/etudiant/attestation/<?= $soutenance['id'] ?>" class="quick-action">
                        <i class="fas fa-certificate"></i>
                        <span>Attestation</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-info-circle"></i> Informations pratiques</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <span>Présentez-vous 15 minutes avant l'heure prévue.</span>
                </div>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Munissez-vous de votre carte d'étudiant.</span>
                </div>
            </div>
        </div>
    </div>
    
    <?php else: ?>
    <div class="card">
        <div class="empty-state">
            <i class="fas fa-calendar-plus" style="font-size: 64px;"></i>
            <h4>Pas encore de soutenance</h4>
            <p>Vous n'avez pas encore de soutenance planifiée.</p>
            <p style="color: var(--text-light);">Contactez le secrétariat pour plus d'informations.</p>
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