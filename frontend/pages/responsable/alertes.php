<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-bell"></i> Alertes</h1>
    </div>
    
    <?php if (empty($alertes)): ?>
    <div class="card">
        <div class="empty-state">
            <i class="fas fa-bell-slash" style="font-size: 64px;"></i>
            <h4>Aucune alerte</h4>
            <p>Tout est en ordre !</p>
        </div>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="card-body">
            <?php foreach ($alertes as $alerte): ?>
            <div class="alert alert-<?= $alerte['type'] ?>">
                <i class="fas <?= $alerte['type'] === 'warning' ? 'fa-exclamation-triangle' : 
                               ($alerte['type'] === 'danger' ? 'fa-times-circle' : 'fa-info-circle') ?>"></i>
                <span><?= htmlspecialchars($alerte['message']) ?></span>
                <?php if ($alerte['lien'] ?? false): ?>
                <a href="<?= $alerte['lien'] ?>" class="btn btn-sm btn-primary" style="margin-left: auto;">
                    Voir
                </a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-cog"></i> Paramètres des alertes</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Type d'alertes à recevoir</label>
                <div class="form-check">
                    <input type="checkbox" id="alerte_pv" checked>
                    <label for="alerte_pv">PV en attente de validation</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" id="alerte_jury" checked>
                    <label for="alerte_jury">Jurys incomplets</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" id="alerte_convocation" checked>
                    <label for="alerte_convocation">Convocations non envoyées</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" id="alerte_conflit">
                    <label for="alerte_conflit">Conflits de planning</label>
                </div>
            </div>
            <button class="btn btn-primary" onclick="saveAlertes()">
                <i class="fas fa-save"></i> Enregistrer les paramètres
            </button>
        </div>
    </div>
</div>

<script>
function saveAlertes() {
    showToast('Paramètres des alertes enregistrés', 'success');
}
</script>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>