<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-history"></i> Journal d'audit</h1>
    </div>
    
    <div class="filters">
        <form method="GET" action="/admin/audit" class="filter-form">
            <div class="filter-group">
                <label>Recherche</label>
                <input type="text" name="search" placeholder="Action, description..." value="<?= $_GET['search'] ?? '' ?>">
            </div>
            <div class="filter-group">
                <label>Utilisateur</label>
                <select name="user_id">
                    <option value="">Tous</option>
                    <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id'] ?>" <?= ($_GET['user_id'] ?? '') == $user['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($user['nom'] . ' ' . $user['prenom']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <label>Action</label>
                <select name="action">
                    <option value="">Toutes</option>
                    <?php foreach ($actions as $action): ?>
                    <option value="<?= $action ?>" <?= ($_GET['action'] ?? '') == $action ? 'selected' : '' ?>>
                        <?= ucfirst($action) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <label>Date début</label>
                <input type="date" name="date_debut" value="<?= $_GET['date_debut'] ?? '' ?>">
            </div>
            <div class="filter-group">
                <label>Date fin</label>
                <input type="date" name="date_fin" value="<?= $_GET['date_fin'] ?? '' ?>">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrer</button>
            <a href="/admin/audit" class="btn btn-secondary"><i class="fas fa-times"></i> Réinitialiser</a>
        </form>
    </div>
    
    <div class="table-container">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Utilisateur</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="5" class="empty-state">
                            <i class="fas fa-history"></i>
                            <h4>Aucun log d'audit</h4>
                            <p>Aucune activité enregistrée pour le moment.</p>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= formatDate($log['created_at'] ?? '') ?></td>
                        <td>
                            <?= htmlspecialchars($log['nom'] ?? '') ?> 
                            <?= htmlspecialchars($log['prenom'] ?? '') ?>
                            <br>
                            <small class="text-muted"><?= htmlspecialchars($log['email'] ?? '') ?></small>
                        </td>
                        <td><span class="badge badge-info"><?= htmlspecialchars($log['action'] ?? '') ?></span></td>
                        <td><?= htmlspecialchars($log['description'] ?? '') ?></td>
                        <td><?= htmlspecialchars($log['ip_address'] ?? '') ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
        <div class="table-pagination">
            <div class="pagination-info">
                Affichage de <?= ($pagination['current_page'] - 1) * $pagination['per_page'] + 1 ?> 
                à <?= min($pagination['current_page'] * $pagination['per_page'], $pagination['total']) ?> 
                sur <?= $pagination['total'] ?> éléments
            </div>
            <div class="pagination-links">
                <?php if ($pagination['has_prev']): ?>
                <a href="?page=<?= $pagination['prev_page'] ?>&search=<?= $_GET['search'] ?? '' ?>&user_id=<?= $_GET['user_id'] ?? '' ?>&action=<?= $_GET['action'] ?? '' ?>&date_debut=<?= $_GET['date_debut'] ?? '' ?>&date_fin=<?= $_GET['date_fin'] ?? '' ?>">&laquo;</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                <a href="?page=<?= $i ?>&search=<?= $_GET['search'] ?? '' ?>&user_id=<?= $_GET['user_id'] ?? '' ?>&action=<?= $_GET['action'] ?? '' ?>&date_debut=<?= $_GET['date_debut'] ?? '' ?>&date_fin=<?= $_GET['date_fin'] ?? '' ?>" class="<?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
                <?php endfor; ?>
                
                <?php if ($pagination['has_next']): ?>
                <a href="?page=<?= $pagination['next_page'] ?>&search=<?= $_GET['search'] ?? '' ?>&user_id=<?= $_GET['user_id'] ?? '' ?>&action=<?= $_GET['action'] ?? '' ?>&date_debut=<?= $_GET['date_debut'] ?? '' ?>&date_fin=<?= $_GET['date_fin'] ?? '' ?>">&raquo;</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>