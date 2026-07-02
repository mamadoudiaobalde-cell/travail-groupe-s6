<?php
$role = $_SESSION['user_role'] ?? '';
$name = $_SESSION['user_name'] ?? '';
?>

<div class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <i class="fas fa-graduation-cap"></i>
            <span>Gestion Soutenances</span>
        </div>
        <div class="sidebar-user">
            <i class="fas fa-user-circle"></i>
            <div>
                <strong><?= htmlspecialchars($name) ?></strong>
                <span class="badge badge-<?= $role ?>"><?= getRoleLabel($role) ?></span>
            </div>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="/dashboard" class="<?= isActive('/dashboard') ? 'active' : '' ?>">
                    <i class="fas fa-home"></i>
                    <span>Tableau de bord</span>
                </a>
            </li>
            
            <?php if ($role === 'admin'): ?>
            <li class="nav-divider">Administration</li>
            <li>
                <a href="/admin/utilisateurs" class="<?= isActive('/admin/utilisateurs') ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>Utilisateurs</span>
                </a>
            </li>
            <li>
                <a href="/admin/salles" class="<?= isActive('/admin/salles') ? 'active' : '' ?>">
                    <i class="fas fa-building"></i>
                    <span>Salles</span>
                </a>
            </li>
            <li>
                <a href="/admin/audit" class="<?= isActive('/admin/audit') ? 'active' : '' ?>">
                    <i class="fas fa-history"></i>
                    <span>Journal d'audit</span>
                </a>
            </li>
            <li>
                <a href="/admin/config" class="<?= isActive('/admin/config') ? 'active' : '' ?>">
                    <i class="fas fa-cog"></i>
                    <span>Configuration</span>
                </a>
            </li>
            <?php endif; ?>
            
            <?php if (in_array($role, ['admin', 'secretaire'])): ?>
            <li class="nav-divider">Gestion</li>
            <li>
                <a href="/secretaire/soutenances" class="<?= isActive('/secretaire/soutenances') ? 'active' : '' ?>">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Soutenances</span>
                </a>
            </li>
            <li>
                <a href="/secretaire/salles" class="<?= isActive('/secretaire/salles') ? 'active' : '' ?>">
                    <i class="fas fa-door-open"></i>
                    <span>Salles</span>
                </a>
            </li>
            <li>
                <a href="/secretaire/convocations" class="<?= isActive('/secretaire/convocations') ? 'active' : '' ?>">
                    <i class="fas fa-envelope"></i>
                    <span>Convocations</span>
                </a>
            </li>
            <?php endif; ?>
            
            <?php if ($role === 'enseignant'): ?>
            <li class="nav-divider">Enseignant</li>
            <li>
                <a href="/enseignant/mes-soutenances" class="<?= isActive('/enseignant/mes-soutenances') ? 'active' : '' ?>">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Mes soutenances</span>
                </a>
            </li>
            <li>
                <a href="/enseignant/participations" class="<?= isActive('/enseignant/participations') ? 'active' : '' ?>">
                    <i class="fas fa-handshake"></i>
                    <span>Participations</span>
                </a>
            </li>
            <li>
                <a href="/enseignant/jury-confirm" class="<?= isActive('/enseignant/jury-confirm') ? 'active' : '' ?>">
                    <i class="fas fa-check-double"></i>
                    <span>Confirmations jury</span>
                </a>
            </li>
            <?php endif; ?>
            
            <?php if ($role === 'etudiant'): ?>
            <li class="nav-divider">Étudiant</li>
            <li>
                <a href="/etudiant/ma-soutenance" class="<?= isActive('/etudiant/ma-soutenance') ? 'active' : '' ?>">
                    <i class="fas fa-user-graduate"></i>
                    <span>Ma soutenance</span>
                </a>
            </li>
            <li>
                <a href="/etudiant/resultats" class="<?= isActive('/etudiant/resultats') ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar"></i>
                    <span>Résultats</span>
                </a>
            </li>
            <li>
                <a href="/etudiant/historique" class="<?= isActive('/etudiant/historique') ? 'active' : '' ?>">
                    <i class="fas fa-history"></i>
                    <span>Historique</span>
                </a>
            </li>
            <?php endif; ?>
            
            <?php if ($role === 'responsable'): ?>
            <li class="nav-divider">Responsable</li>
            <li>
                <a href="/responsable/statistiques" class="<?= isActive('/responsable/statistiques') ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie"></i>
                    <span>Statistiques</span>
                </a>
            </li>
            <li>
                <a href="/responsable/exports" class="<?= isActive('/responsable/exports') ? 'active' : '' ?>">
                    <i class="fas fa-file-export"></i>
                    <span>Exports</span>
                </a>
            </li>
            <li>
                <a href="/responsable/alertes" class="<?= isActive('/responsable/alertes') ? 'active' : '' ?>">
                    <i class="fas fa-bell"></i>
                    <span>Alertes</span>
                </a>
            </li>
            <?php endif; ?>
            
            <li class="nav-divider">Compte</li>
            <li>
                <a href="/profile" class="<?= isActive('/profile') ? 'active' : '' ?>">
                    <i class="fas fa-user"></i>
                    <span>Mon profil</span>
                </a>
            </li>
            <li>
                <a href="/logout" class="text-danger">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

<style>
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 260px;
    height: 100%;
    background: white;
    box-shadow: 2px 0 8px rgba(0,0,0,0.1);
    z-index: 999;
    overflow-y: auto;
    transition: transform 0.3s ease;
}

.sidebar-header {
    padding: 20px;
    border-bottom: 1px solid #eee;
}

.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 18px;
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 15px;
}

.sidebar-user {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
}

.sidebar-user i {
    font-size: 28px;
    color: var(--text-light);
}

.sidebar-user div {
    flex: 1;
}

.sidebar-user strong {
    display: block;
    font-size: 14px;
}

.sidebar-nav {
    padding: 10px 0;
}

.sidebar-nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-nav li {
    margin: 0;
}

.sidebar-nav .nav-divider {
    padding: 10px 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--text-light);
    letter-spacing: 0.5px;
}

.sidebar-nav a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 20px;
    color: var(--text-color);
    text-decoration: none;
    transition: all 0.3s;
    font-size: 14px;
    border-left: 3px solid transparent;
}

.sidebar-nav a:hover {
    background: #f8f9fa;
    color: var(--primary-color);
}

.sidebar-nav a.active {
    background: #e3f2fd;
    color: var(--primary-color);
    border-left-color: var(--primary-color);
}

.sidebar-nav a i {
    width: 20px;
    text-align: center;
}

.sidebar-nav .text-danger {
    color: var(--danger-color) !important;
}

.sidebar-nav .text-danger:hover {
    background: #ffebee;
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.open {
        transform: translateX(0);
    }
}
</style>

<?php
function isActive($path) {
    $current = $_SERVER['REQUEST_URI'] ?? '';
    return strpos($current, $path) === 0 ? true : false;
}
?>