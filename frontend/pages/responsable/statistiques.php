<?php require_once FRONTEND_PATH . '/templates/header.php';
$includeCharts = true; ?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-chart-pie"></i> Statistiques</h1>
    </div>
    
    <div class="filters">
        <form method="GET" action="/responsable/statistiques" class="filter-form">
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
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrer</button>
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
            'value' => number_format($stats['duree_moyenne'] ?? 0, 1),
            'label' => 'Durée moyenne (min)',
            'icon' => 'fa-clock',
            'iconClass' => 'purple'
        ]); ?>
    </div>
    
    <div class="dashboard-charts">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-chart-bar"></i> Soutenances par filière</h3>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="filiereChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-chart-pie"></i> Répartition des mentions</h3>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="mentionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-table"></i> Détail des statistiques</h3>
        </div>
        <div class="card-body">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Indicateur</th>
                            <th>Valeur</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Total soutenances</td>
                            <td><strong><?= $stats['total'] ?? 0 ?></strong></td>
                        </tr>
                        <tr>
                            <td>Planifiées</td>
                            <td><strong><?= $stats['planifiees'] ?? 0 ?></strong></td>
                        </tr>
                        <tr>
                            <td>En cours</td>
                            <td><strong><?= $stats['en_cours'] ?? 0 ?></strong></td>
                        </tr>
                        <tr>
                            <td>Terminées</td>
                            <td><strong><?= $stats['terminees'] ?? 0 ?></strong></td>
                        </tr>
                        <tr>
                            <td>Annulées</td>
                            <td><strong><?= $stats['annulees'] ?? 0 ?></strong></td>
                        </tr>
                        <tr>
                            <td>Durée moyenne</td>
                            <td><strong><?= number_format($stats['duree_moyenne'] ?? 0, 1) ?> minutes</strong></td>
                        </tr>
                        <tr>
                            <td>PV valides</td>
                            <td><strong><?= $statsPv['valides'] ?? 0 ?></strong></td>
                        </tr>
                        <tr>
                            <td>Note moyenne</td>
                            <td><strong><?= number_format($statsPv['moyenne_notes'] ?? 0, 2) ?>/20</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartManager = new ChartManager();
    
    // Graphique des filières
    <?php if (!empty($statsFiliere)): ?>
    const filiereLabels = <?= json_encode(array_column($statsFiliere, 'filiere')) ?>;
    const filiereData = <?= json_encode(array_column($statsFiliere, 'total')) ?>;
    const filiereColors = ['#1976d2', '#2e7d32', '#ed6c02', '#d32f2f', '#9c27b0'];
    
    chartManager.createBarChart('filiereChart', filiereLabels, [{
        label: 'Soutenances',
        data: filiereData,
        backgroundColor: filiereColors.slice(0, filiereData.length),
        borderColor: filiereColors.slice(0, filiereData.length).map(c => c + 'cc'),
        borderWidth: 2
    }]);
    <?php endif; ?>
    
    // Graphique des mentions
    <?php if (!empty($statsPvByMention)): ?>
    const mentionLabels = <?= json_encode(array_column($statsPvByMention, 'mention')) ?>;
    const mentionData = <?= json_encode(array_column($statsPvByMention, 'count')) ?>;
    const mentionColors = {
        'excellent': '#1b5e20',
        'tres_bien': '#0d47a1',
        'bien': '#00695c',
        'assez_bien': '#e65100',
        'passable': '#f57f17',
        'insuffisant': '#b71c1c'
    };
    
    const colors = mentionLabels.map(m => mentionColors[m] || '#999');
    
    chartManager.createPieChart('mentionChart', mentionLabels.map(getMentionLabel), mentionData, colors);
    <?php endif; ?>
});
</script>

<style>
.chart-container {
    height: 300px;
    position: relative;
}
</style>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>