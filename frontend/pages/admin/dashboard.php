<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="dashboard">
    <div class="dashboard-welcome">
        <h2>Bonjour <?= htmlspecialchars($_SESSION['user_name']) ?> 👋</h2>
        <p>Bienvenue sur le tableau de bord administrateur.</p>
    </div>
    
    <div class="dashboard-stats">
        <?php include_component('stat-card', [
            'value' => $stats['total_utilisateurs'],
            'label' => 'Utilisateurs',
            'icon' => 'fa-users',
            'iconClass' => 'blue'
        ]); ?>
        <?php include_component('stat-card', [
            'value' => $stats['total_soutenances'],
            'label' => 'Soutenances',
            'icon' => 'fa-calendar-alt',
            'iconClass' => 'green'
        ]); ?>
        <?php include_component('stat-card', [
            'value' => $stats['total_salles'],
            'label' => 'Salles',
            'icon' => 'fa-building',
            'iconClass' => 'orange'
        ]); ?>
        <?php include_component('stat-card', [
            'value' => $stats['soutenances_mois'],
            'label' => 'Ce mois',
            'icon' => 'fa-calendar-day',
            'iconClass' => 'purple'
        ]); ?>
    </div>
    
    <div class="admin-grid">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-history"></i> Dernières activités</h3>
            </div>
            <div class="card-body">
                <?php if (empty($auditLogs)): ?>
                <div class="empty-state">
                    <p>Aucune activité récente</p>
                </div>
                <?php else: ?>
                <?php foreach ($auditLogs as $log): ?>
                <div class="activity-item">
                    <div class="activity-icon <?= $log['action'] ?>">
                        <i class="fas <?= $log['action'] === 'login' ? 'fa-sign-in-alt' : 
                                        ($log['action'] === 'create' ? 'fa-plus' : 
                                        ($log['action'] === 'update' ? 'fa-edit' : 'fa-trash')) ?>"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">
                            <?= htmlspecialchars($log['prenom'] ?? '') ?> <?= htmlspecialchars($log['nom'] ?? '') ?>
                            <span class="text-muted">- <?= htmlspecialchars($log['action']) ?></span>
                        </div>
                        <div class="activity-time"><?= formatDate($log['created_at']) ?></div>
                    </div>
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
                    <a href="/admin/utilisateurs" class="quick-action">
                        <i class="fas fa-user-plus"></i>
                        <span>Gérer les utilisateurs</span>
                    </a>
                    <a href="/secretaire/soutenances/creer" class="quick-action">
                        <i class="fas fa-plus-circle"></i>
                        <span>Créer une soutenance</span>
                    </a>
                    <a href="/admin/audit" class="quick-action">
                        <i class="fas fa-history"></i>
                        <span>Voir l'audit</span>
                    </a>
                    <a href="/admin/config" class="quick-action">
                        <i class="fas fa-cog"></i>
                        <span>Configuration</span>
                    </a>
<?php
session_start();
$page_title = 'Dashboard Admin';
require_once '../../templates/header.php';
require_once '../../templates/navbar.php';
?>

<div class="container mt-4">
    <h1>Dashboard Administrateur</h1>
    <p>Bienvenue <?= $_SESSION['user']['name'] ?? 'Admin' ?> !</p>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body text-center">
                    <h2>0</h2>
                    <p>Utilisateurs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <h2>0</h2>
                    <p>Salles</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body text-center">
                    <h2>0</h2>
                    <p>Soutenances</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body text-center">
                    <h2>0</h2>
                    <p>Ce mois</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>
<?php require_once '../../templates/footer.php'; ?>
