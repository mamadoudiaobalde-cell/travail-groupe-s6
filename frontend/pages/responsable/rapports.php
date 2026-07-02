<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-file-alt"></i> Rapports</h1>
    </div>
    
    <div class="filters">
        <form method="GET" action="/responsable/rapports" class="filter-form">
            <div class="filter-group">
                <label>Filière</label>
                <select name="filiere">
                    <option value="">Toutes</option>
                    <?php foreach (['Informatique', 'Mathematiques', 'Physique', 'Chimie', 'Biologie'] as $f): ?>
                    <option value="<?= $f ?>" <?= ($_GET['filiere'] ?? '') === $f ? 'selected' : '' ?>>
                        <?= $f ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <label>Année académique</label>
                <select name="annee_academique">
                    <option value="">Toutes</option>
                    <?php foreach (['2022-2023', '2023-2024', '2024-2025'] as $a): ?>
                    <option value="<?= $a ?>" <?= ($_GET['annee_academique'] ?? '') === $a ? 'selected' : '' ?>>
                        <?= $a ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-eye"></i> Générer</button>
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
            'value' => $stats['planifiees'] ?? 0,
            'label' => 'Planifiées',
            'icon' => 'fa-clock',
            'iconClass' => 'orange'
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
    </div>
    
    <div class="admin-grid">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-chart-bar"></i> Synthèse par filière</h3>
            </div>
            <div class="card-body">
                <?php if (empty($statsFiliere)): ?>
                <div class="empty-state">
                    <p>Aucune donnée disponible</p>
                </div>
                <?php else: ?>
                <div class="table-wrapper">
                    <table class="table table-compact">
                        <thead>
                            <tr>
                                <th>Filière</th>
                                <th>Total</th>
                                <th>Terminées</th>
                                <th>Taux réussite</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($statsFiliere as $f): ?>
                            <tr>
                                <td><?= htmlspecialchars($f['filiere']) ?></td>
                                <td><?= $f['total'] ?></td>
                                <td><?= $f['terminees'] ?? 0 ?></td>
                                <td>
                                    <?php 
                                    $taux = $f['total'] > 0 ? round(($f['terminees'] ?? 0) / $f['total'] * 100, 1) : 0;
                                    ?>
                                    <span class="badge badge-<?= $taux >= 70 ? 'success' : ($taux >= 50 ? 'warning' : 'danger') ?>">
                                        <?= $taux ?>%
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-file-pdf"></i> Actions</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <button onclick="genererRapportPDF()" class="btn btn-primary">
                        <i class="fas fa-file-pdf"></i> Générer le rapport PDF
                    </button>
                    <button onclick="exporterRapport()" class="btn btn-success">
                        <i class="fas fa-file-export"></i> Exporter en CSV
                    </button>
                    <button onclick="imprimerRapport()" class="btn btn-secondary">
                        <i class="fas fa-print"></i> Imprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function genererRapportPDF() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = '/responsable/rapports/pdf?' + params.toString();
}

function exporterRapport() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = '/responsable/rapports/export?' + params.toString();
}

function imprimerRapport() {
    window.print();
}
</script>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>