<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="dashboard">
    <div class="dashboard-welcome">
        <h2>Bonjour <?= htmlspecialchars($_SESSION['user_name']) ?> 📋</h2>
        <p>Bienvenue sur le tableau de bord du secrétariat. Gérez les soutenances et les convocations.</p>
    </div>
    
    <div class="dashboard-stats">
        <?php include_component('cards/stat-card', [
            'value' => $stats['total_soutenances'],
            'label' => 'Total soutenances',
            'icon' => 'fa-calendar-alt',
            'iconClass' => 'blue'
        ]); ?>
        <?php include_component('cards/stat-card', [
            'value' => $stats['soutenances_aujourdhui'],
            'label' => 'Aujourd\'hui',
            'icon' => 'fa-calendar-day',
            'iconClass' => 'green'
        ]); ?>
        <?php include_component('cards/stat-card', [
            'value' => $stats['soutenances_semaine'],
            'label' => 'Cette semaine',
            'icon' => 'fa-calendar-week',
            'iconClass' => 'orange'
        ]); ?>
        <?php include_component('cards/stat-card', [
            'value' => $stats['soutenances_mois'],
            'label' => 'Ce mois',
            'icon' => 'fa-calendar-month',
            'iconClass' => 'purple'
        ]); ?>
    </div>
    
    <div class="admin-grid">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-clock"></i> Soutenances récentes</h3>
                <a href="/secretaire/soutenances" class="btn btn-sm btn-primary">Voir tout</a>
            </div>
            <div class="card-body">
                <?php if (empty($soutenancesRecentes)): ?>
                <div class="empty-state">
                    <i class="fas fa-calendar-plus"></i>
                    <p>Aucune soutenance planifiée</p>
                </div>
                <?php else: ?>
                <?php foreach ($soutenancesRecentes as $s): ?>
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
                    <a href="/secretaire/soutenances/voir/<?= $s['id'] ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-bolt"></i> Actions rapides</h3>
            </div>
            <div class="card-body">
                <div class="quick-actions">
                    <a href="/secretaire/soutenances/creer" class="quick-action">
                        <i class="fas fa-plus-circle"></i>
                        <span>Nouvelle soutenance</span>
                    </a>
                    <a href="/secretaire/salles" class="quick-action">
                        <i class="fas fa-door-open"></i>
                        <span>Gérer les salles</span>
                    </a>
                    <a href="/secretaire/convocations" class="quick-action">
                        <i class="fas fa-envelope"></i>
                        <span>Convocations</span>
                    </a>
                    <a href="/secretaire/rapports" class="quick-action">
                        <i class="fas fa-file-alt"></i>
                        <span>Rapports</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>