<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-file-alt"></i> Rapports</h1>
        <div class="page-actions">
            <button onclick="exportRapport()" class="btn btn-primary">
                <i class="fas fa-file-export"></i> Exporter
            </button>
        </div>
    </div>
    
    <div class="filters">
        <form method="GET" action="/secretaire/rapports" class="filter-form">
            <div class="filter-group">
                <label>Période</label>
                <select name="periode">
                    <option value="mois" <?= ($_GET['periode'] ?? 'mois') === 'mois' ? 'selected' : '' ?>>Ce mois</option>
                    <option value="trimestre" <?= ($_GET['periode'] ?? '') === 'trimestre' ? 'selected' : '' ?>>Ce trimestre</option>
                    <option value="semestre" <?= ($_GET['periode'] ?? '') === 'semestre' ? 'selected' : '' ?>>Ce semestre</option>
                    <option value="annee" <?= ($_GET['periode'] ?? '') === 'annee' ? 'selected' : '' ?>>Cette année</option>
                    <option value="personnalise" <?= ($_GET['periode'] ?? '') === 'personnalise' ? 'selected' : '' ?>>Personnalisé</option>
                </select>
            </div>
            <div class="filter-group" id="dateRange" style="display: <?= ($_GET['periode'] ?? '') === 'personnalise' ? 'flex' : 'none' ?>; gap: 10px;">
                <input type="date" name="date_debut" value="<?= $_GET['date_debut'] ?? '' ?>">
                <span>à</span>
                <input type="date" name="date_fin" value="<?= $_GET['date_fin'] ?? '' ?>">
            </div>
            <div class="filter-group">
                <label>Filière</label>
                <select name="filiere">
                    <option value="">Toutes</option>
                    <?php foreach ($filieres as $f): ?>
                    <option value="<?= $f ?>" <?= ($_GET['filiere'] ?? '') === $f ? 'selected' : '' ?>>
                        <?= $f ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-eye"></i> Voir</button>
        </form>
    </div>
    
    <div class="dashboard-stats">
        <?php include_component('cards/stat-card', [
            'value' => $stats['total'] ?? 0,
            'label' => 'Total soutenances',
            'icon' => 'fa-calendar-alt',
            'iconClass' => 'blue'
        ]); ?>
        <?php include_component('cards/stat-card', [
            'value' => $stats['terminees'] ?? 0,
            'label' => 'Terminées',
            'icon' => 'fa-check-circle',
            'iconClass' => 'green'
        ]); ?>
        <?php include_component('cards/stat-card', [
            'value' => $stats['annulees'] ?? 0,
            'label' => 'Annulées',
            'icon' => 'fa-times-circle',
            'iconClass' => 'red'
        ]); ?>
        <?php include_component('cards/stat-card', [
            'value' => number_format($stats['duree_moyenne'] ?? 0, 1),
            'label' => 'Durée moyenne (min)',
            'icon' => 'fa-clock',
            'iconClass' => 'orange'
        ]); ?>
    </div>
    
    <?php if (!empty($soutenances)): ?>
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-list"></i> Détail des soutenances</h3>
        </div>
        <div class="card-body">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Étudiant</th>
                            <th>Date</th>
                            <th>Filière</th>
                            <th>Statut</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($soutenances as $s): ?>
                        <tr>
                            <td><?= htmlspecialchars($s['titre']) ?></td>
                            <td><?= htmlspecialchars($s['etudiant_nom'] ?? '') ?> <?= htmlspecialchars($s['etudiant_prenom'] ?? '') ?></td>
                            <td><?= formatDate($s['date_heure']) ?></td>
                            <td><?= htmlspecialchars($s['filiere']) ?></td>
                            <td><span class="badge badge-<?= $s['statut'] ?>"><?= getStatusLabel($s['statut']) ?></span></td>
                            <td><?= $s['note'] ?? '-' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
document.querySelector('[name="periode"]').addEventListener('change', function() {
    const dateRange = document.getElementById('dateRange');
    dateRange.style.display = this.value === 'personnalise' ? 'flex' : 'none';
});

function exportRapport() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = '/secretaire/rapports/export?' + params.toString();
}
</script>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>