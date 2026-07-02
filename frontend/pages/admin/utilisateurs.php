<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-users"></i> Gestion des utilisateurs</h1>
    </div>
    
    <div class="filters">
        <form method="GET" action="/admin/utilisateurs" class="filter-form">
            <div class="filter-group">
                <label>Recherche</label>
                <input type="text" name="search" placeholder="Nom, email..." value="<?= $_GET['search'] ?? '' ?>">
            </div>
            <div class="filter-group">
                <label>Rôle</label>
                <select name="role">
                    <option value="">Tous</option>
                    <?php foreach ($roles as $role): ?>
                    <option value="<?= $role ?>" <?= ($_GET['role'] ?? '') === $role ? 'selected' : '' ?>>
                        <?= getRoleLabel($role) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <label>Statut</label>
                <select name="statut">
                    <option value="">Tous</option>
                    <?php foreach ($statuts as $statut): ?>
                    <option value="<?= $statut ?>" <?= ($_GET['statut'] ?? '') === $statut ? 'selected' : '' ?>>
                        <?= getStatusLabel($statut) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrer</button>
            <a href="/admin/utilisateurs" class="btn btn-secondary"><i class="fas fa-times"></i> Réinitialiser</a>
        </form>
    </div>
    
    <?php include_component('tables/user-table', [
        'users' => $users,
        'pagination' => $pagination
    ]); ?>
</div>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>