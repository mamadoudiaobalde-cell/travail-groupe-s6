<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-file-export"></i> Exports</h1>
    </div>
    
    <div class="admin-grid">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-calendar-alt"></i> Exporter les soutenances</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="/responsable/exports" class="export-form">
                    <input type="hidden" name="type" value="soutenances">
                    
                    <div class="form-group">
                        <label>Filière</label>
                        <select name="filiere" class="form-control">
                            <option value="">Toutes</option>
                            <?php foreach ($filieres as $f): ?>
                            <option value="<?= $f ?>"><?= $f ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="statut" class="form-control">
                            <option value="">Tous</option>
                            <?php foreach ($statuts as $s): ?>
                            <option value="<?= $s ?>"><?= getStatusLabel($s) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Format</label>
                        <select name="format" class="form-control">
                            <option value="csv">CSV</option>
                            <option value="excel">Excel</option>
                            <option value="json">JSON</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-download"></i> Exporter
                    </button>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-users"></i> Exporter les utilisateurs</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="/responsable/exports" class="export-form">
                    <input type="hidden" name="type" value="utilisateurs">
                    
                    <div class="form-group">
                        <label>Rôle</label>
                        <select name="role" class="form-control">
                            <option value="">Tous</option>
                            <option value="etudiant">Étudiant</option>
                            <option value="enseignant">Enseignant</option>
                            <option value="secretaire">Secrétaire</option>
                            <option value="responsable">Responsable</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="statut" class="form-control">
                            <option value="">Tous</option>
                            <option value="actif">Actif</option>
                            <option value="inactif">Inactif</option>
                            <option value="suspendu">Suspendu</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Format</label>
                        <select name="format" class="form-control">
                            <option value="csv">CSV</option>
                            <option value="excel">Excel</option>
                            <option value="json">JSON</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-download"></i> Exporter
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-info-circle"></i> Informations</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <span>Les exports sont générés au format sélectionné et téléchargés automatiquement.</span>
            </div>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Les exports volumineux peuvent prendre quelques secondes.</span>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.export-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération...';
        submitBtn.disabled = true;
    });
});
</script>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>