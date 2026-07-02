<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-cog"></i> Configuration</h1>
    </div>
    
    <form method="POST" action="/admin/config/sauvegarder" data-validate-form>
        <div class="admin-grid">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-globe"></i> Paramètres généraux</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="site_name">Nom du site</label>
                        <input type="text" id="site_name" name="site_name" class="form-control" 
                               value="<?= $_ENV['APP_NAME'] ?? 'Gestion Soutenances' ?>">
                    </div>
                    <div class="form-group">
                        <label for="site_email">Email de contact</label>
                        <input type="email" id="site_email" name="site_email" class="form-control" 
                               value="<?= $_ENV['MAIL_FROM'] ?? 'contact@univ.edu' ?>">
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-clock"></i> Paramètres des soutenances</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="duree_defaut">Durée par défaut (minutes)</label>
                        <select id="duree_defaut" name="duree_defaut" class="form-control">
                            <option value="15">15 minutes</option>
                            <option value="20">20 minutes</option>
                            <option value="30" selected>30 minutes</option>
                            <option value="45">45 minutes</option>
                            <option value="60">60 minutes</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="delai_annulation">Délai d'annulation (heures)</label>
                        <input type="number" id="delai_annulation" name="delai_annulation" class="form-control" 
                               value="48" min="1" max="168">
                        <small class="form-help">Délai minimum avant la soutenance pour annuler</small>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-envelope"></i> Paramètres email</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="mail_host">Serveur SMTP</label>
                        <input type="text" id="mail_host" name="mail_host" class="form-control" 
                               value="<?= $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com' ?>">
                    </div>
                    <div class="form-group">
                        <label for="mail_port">Port SMTP</label>
                        <input type="number" id="mail_port" name="mail_port" class="form-control" 
                               value="<?= $_ENV['MAIL_PORT'] ?? 587 ?>">
                    </div>
                    <div class="form-group">
                        <label for="mail_username">Nom d'utilisateur</label>
                        <input type="text" id="mail_username" name="mail_username" class="form-control" 
                               value="<?= $_ENV['MAIL_USERNAME'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="mail_password">Mot de passe</label>
                        <input type="password" id="mail_password" name="mail_password" class="form-control" 
                               placeholder="Laissez vide pour ne pas modifier">
                        <small class="form-help">Laissez vide pour conserver le mot de passe actuel</small>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-database"></i> Maintenance</h3>
                </div>
                <div class="card-body">
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <button type="button" onclick="nettoyerLogs()" class="btn btn-warning">
                            <i class="fas fa-trash-alt"></i> Nettoyer les logs d'audit (30 jours)
                        </button>
                        <button type="button" onclick="exporterDonnees()" class="btn btn-success">
                            <i class="fas fa-file-export"></i> Exporter toutes les données
                        </button>
                        <button type="button" onclick="reinitialiserCache()" class="btn btn-secondary">
                            <i class="fas fa-sync"></i> Réinitialiser le cache
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-actions" style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Sauvegarder la configuration
            </button>
            <a href="/admin/dashboard" class="btn btn-secondary">
                <i class="fas fa-times"></i> Annuler
            </a>
        </div>
    </form>
</div>

<script>
function nettoyerLogs() {
    if (!confirm('Nettoyer les logs d\'audit de plus de 30 jours ?')) return;
    
    fetch('/admin/config/nettoyer-logs', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(() => showToast('Erreur lors du nettoyage', 'error'));
}

function exporterDonnees() {
    window.location.href = '/admin/config/exporter-donnees';
}

function reinitialiserCache() {
    if (!confirm('Réinitialiser le cache ?')) return;
    
    fetch('/admin/config/reinitialiser-cache', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(() => showToast('Erreur lors de la réinitialisation', 'error'));
}
</script>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>