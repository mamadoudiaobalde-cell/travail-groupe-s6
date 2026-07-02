<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="dashboard">
    <div class="dashboard-welcome">
        <h2>Bonjour <?= htmlspecialchars($_SESSION['user_name']) ?> 📊</h2>
        <p>Bienvenue sur le tableau de bord responsable. Supervisez les soutenances et les résultats.</p>
    </div>
    
    <div class="dashboard-stats">
        <?php include_component('cards/stat-card', [
            'value' => $stats['total_soutenances'] ?? 0,
            'label' => 'Total soutenances',
            'icon' => 'fa-calendar-alt',
            'iconClass' => 'blue'
        ]); ?>
        <?php include_component('cards/stat-card', [
            'value' => $stats['soutenances_terminees'] ?? 0,
            'label' => 'Soutenances terminées',
            'icon' => 'fa-check-circle',
            'iconClass' => 'green'
        ]); ?>
        <?php include_component('cards/stat-card', [
            'value' => $stats['pv_valides'] ?? 0,
            'label' => 'PV validés',
            'icon' => 'fa-file-alt',
            'iconClass' => 'purple'
        ]); ?>
        <?php include_component('cards/stat-card', [
            'value' => $stats['pv_attente'] ?? 0,
            'label' => 'PV en attente',
            'icon' => 'fa-clock',
            'iconClass' => 'orange'
        ]); ?>
    </div>
    
    <div class="admin-grid">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-clock"></i> Dernières soutenances</h3>
                <a href="/responsable/rapports" class="btn btn-sm btn-primary">Voir tout</a>
            </div>
            <div class="card-body">
                <?php if (empty($soutenances)): ?>
                <div class="empty-state">
                    <i class="fas fa-calendar-alt"></i>
                    <p>Aucune soutenance récente</p>
                </div>
                <?php else: ?>
                <?php foreach ($soutenances as $s): ?>
                <div class="activity-item">
                    <div class="activity-content" style="flex: 1;">
                        <div class="activity-title">
                            <strong><?= htmlspecialchars($s['titre']) ?></strong>
                            <span class="badge badge-<?= $s['statut'] ?>"><?= getStatusLabel($s['statut']) ?></span>
                        </div>
                        <div class="activity-time">
                            <i class="fas fa-user"></i> <?= htmlspecialchars($s['etudiant_nom'] ?? '') ?> <?= htmlspecialchars($s['etudiant_prenom'] ?? '') ?>
                            <i class="fas fa-calendar ml-1"></i> <?= formatDate($s['date_heure']) ?>
                        </div>
                    </div>
                    <a href="/responsable/soutenances/voir/<?= $s['id'] ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-chart-pie"></i> Répartition par filière</h3>
            </div>
            <div class="card-body">
                <?php if (empty($statsFiliere)): ?>
                <div class="empty-state">
                    <i class="fas fa-chart-pie"></i>
                    <p>Aucune donnée disponible</p>
                </div>
                <?php else: ?>
                <?php foreach ($statsFiliere as $f): ?>
                <div class="detail-row">
                    <label><?= htmlspecialchars($f['filiere']) ?></label>
                    <p>
                        <strong><?= $f['total'] ?></strong> soutenances
                        <?php if ($f['terminees'] > 0): ?>
                        <span class="badge badge-success"><?= $f['terminees'] ?> terminées</span>
                        <?php endif; ?>
                    </p>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-bolt"></i> Actions rapides</h3>
        </div>
        <div class="card-body">
            <div class="quick-actions">
                <a href="/responsable/statistiques" class="quick-action">
                    <i class="fas fa-chart-pie"></i>
                    <span>Statistiques</span>
                </a>
                <a href="/responsable/exports" class="quick-action">
                    <i class="fas fa-file-export"></i>
                    <span>Exports</span>
                </a>
                <a href="/responsable/alertes" class="quick-action">
                    <i class="fas fa-bell"></i>
                    <span>Alertes</span>
                </a>
                <a href="/responsable/rapports" class="quick-action">
                    <i class="fas fa-file-alt"></i>
                    <span>Rapports</span>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #f5f5f5;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-row label {
    font-weight: 500;
    color: var(--text-color);
}

.ml-1 {
    margin-left: 8px;
}
</style>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>