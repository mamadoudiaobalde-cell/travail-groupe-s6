<?php
$role = $_SESSION['user_role'] ?? '';
$name = $_SESSION['user_name'] ?? '';
?>

<nav class="navbar">
    <div class="navbar-brand">
        <a href="/dashboard">
            <i class="fas fa-graduation-cap"></i>
            <span>Gestion Soutenances</span>
        </a>
    </div>
    
    <button class="navbar-toggle" onclick="toggleNavbar()">
        <i class="fas fa-bars"></i>
    </button>
    
    <div class="navbar-menu" id="navbarMenu">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link <?= isActive('/dashboard') ? 'active' : '' ?>" href="/dashboard">
                    <i class="fas fa-home"></i> Tableau de bord
                </a>
            </li>
            
            <?php if ($role === 'admin'): ?>
            <li class="nav-item">
                <a class="nav-link <?= isActive('/admin/utilisateurs') ? 'active' : '' ?>" href="/admin/utilisateurs">
                    <i class="fas fa-users"></i> Utilisateurs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isActive('/admin/audit') ? 'active' : '' ?>" href="/admin/audit">
                    <i class="fas fa-history"></i> Audit
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isActive('/admin/config') ? 'active' : '' ?>" href="/admin/config">
                    <i class="fas fa-cog"></i> Configuration
                </a>
            </li>
            <?php endif; ?>
            
            <?php if (in_array($role, ['admin', 'secretaire'])): ?>
            <li class="nav-item">
                <a class="nav-link <?= isActive('/secretaire/soutenances') ? 'active' : '' ?>" href="/secretaire/soutenances">
                    <i class="fas fa-calendar-alt"></i> Soutenances
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isActive('/secretaire/salles') ? 'active' : '' ?>" href="/secretaire/salles">
                    <i class="fas fa-building"></i> Salles
                </a>
            </li>
            <?php endif; ?>
            
            <?php if ($role === 'enseignant'): ?>
            <li class="nav-item">
                <a class="nav-link <?= isActive('/enseignant/mes-soutenances') ? 'active' : '' ?>" href="/enseignant/mes-soutenances">
                    <i class="fas fa-chalkboard-teacher"></i> Mes soutenances
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isActive('/enseignant/participations') ? 'active' : '' ?>" href="/enseignant/participations">
                    <i class="fas fa-handshake"></i> Participations
                </a>
            </li>
            <?php endif; ?>
            
            <?php if ($role === 'etudiant'): ?>
            <li class="nav-item">
                <a class="nav-link <?= isActive('/etudiant/ma-soutenance') ? 'active' : '' ?>" href="/etudiant/ma-soutenance">
                    <i class="fas fa-user-graduate"></i> Ma soutenance
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isActive('/etudiant/resultats') ? 'active' : '' ?>" href="/etudiant/resultats">
                    <i class="fas fa-chart-bar"></i> Résultats
                </a>
            </li>
            <?php endif; ?>
            
            <?php if ($role === 'responsable'): ?>
            <li class="nav-item">
                <a class="nav-link <?= isActive('/responsable/statistiques') ? 'active' : '' ?>" href="/responsable/statistiques">
                    <i class="fas fa-chart-pie"></i> Statistiques
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isActive('/responsable/exports') ? 'active' : '' ?>" href="/responsable/exports">
                    <i class="fas fa-file-export"></i> Exports
                </a>
            </li>
            <?php endif; ?>
            
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" onclick="toggleDropdown(event)">
                    <i class="fas fa-user"></i> <?= htmlspecialchars($name) ?>
                    <i class="fas fa-chevron-down" style="font-size: 10px;"></i>
                </a>
                <div class="dropdown-menu">
                    <a href="/profile"><i class="fas fa-user-circle"></i> Mon profil</a>
                    <a href="/profile/password"><i class="fas fa-key"></i> Changer mot de passe</a>
                    <div class="dropdown-divider"></div>
                    <a href="/logout" class="text-danger"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<style>
.navbar {
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 0 20px;
    position: sticky;
    top: 0;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 60px;
}

.navbar-brand a {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: var(--primary-color);
    font-weight: 600;
    font-size: 18px;
}

.navbar-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: var(--text-color);
}

.navbar-menu {
    flex: 1;
    display: flex;
    justify-content: flex-end;
}

.navbar-nav {
    display: flex;
    list-style: none;
    gap: 5px;
    margin: 0;
    padding: 0;
    align-items: center;
}

.nav-item {
    position: relative;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    color: var(--text-color);
    text-decoration: none;
    border-radius: 6px;
    transition: all 0.3s;
    font-size: 14px;
}

.nav-link:hover {
    background: #f5f5f5;
    color: var(--primary-color);
}

.nav-link.active {
    background: var(--primary-color);
    color: white;
}

.dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    min-width: 200px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    padding: 8px 0;
    margin-top: 5px;
}

.dropdown-menu.show {
    display: block;
}

.dropdown-menu a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 16px;
    color: var(--text-color);
    text-decoration: none;
    transition: all 0.3s;
    font-size: 14px;
}

.dropdown-menu a:hover {
    background: #f5f5f5;
}

.dropdown-divider {
    height: 1px;
    background: #eee;
    margin: 5px 0;
}

.text-danger {
    color: var(--danger-color) !important;
}

@media (max-width: 768px) {
    .navbar {
        flex-wrap: wrap;
        height: auto;
        padding: 10px 20px;
    }
    
    .navbar-toggle {
        display: block;
    }
    
    .navbar-menu {
        display: none;
        width: 100%;
        flex-direction: column;
    }
    
    .navbar-menu.open {
        display: flex;
    }
    
    .navbar-nav {
        flex-direction: column;
        width: 100%;
        gap: 2px;
    }
    
    .nav-link {
        width: 100%;
        padding: 10px 14px;
    }
    
    .dropdown-menu {
        position: static;
        box-shadow: none;
        border-radius: 0;
        padding-left: 20px;
    }
}
</style>

<script>
function toggleNavbar() {
    document.getElementById('navbarMenu').classList.toggle('open');
}

function toggleDropdown(event) {
    event.preventDefault();
    const dropdown = event.currentTarget.nextElementSibling;
    dropdown.classList.toggle('show');
}

// Fermer les dropdowns en cliquant ailleurs
document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    }
});
</script>

<?php
function isActive($path) {
    $current = $_SERVER['REQUEST_URI'] ?? '';
    return strpos($current, $path) === 0 ? true : false;
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
            <i class="fas fa-graduation-cap"></i> GestSoutenance
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/dashboard">Dashboard</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> <?= $_SESSION['user']['name'] ?? 'Utilisateur' ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/profile">Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/logout">Déconnexion</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
