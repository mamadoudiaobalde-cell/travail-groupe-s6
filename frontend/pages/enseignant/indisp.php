<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-clock"></i> Gestion des indisponibilités</h1>
        <div class="page-actions">
            <button onclick="openModal('indispoModal')" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter une indisponibilité
            </button>
        </div>
    </div>
    
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        <span>Les indisponibilités permettent d'éviter les conflits de planning lors de l'attribution des jurys.</span>
    </div>
    
    <div class="table-container">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Matin</th>
                        <th>Après-midi</th>
                        <th>Motif</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($indisponibilites)): ?>
                    <tr>
                        <td colspan="5" class="empty-state">
                            <i class="fas fa-clock"></i>
                            <h4>Aucune indisponibilité</h4>
                            <p>Vous n'avez pas enregistré d'indisponibilité.</p>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($indisponibilites as $ind): ?>
                    <tr>
                        <td><?= formatDate($ind['date'], 'd/m/Y') ?></td>
                        <td>
                            <?php if ($ind['matin']): ?>
                            <span class="badge badge-danger">Indisponible</span>
                            <?php else: ?>
                            <span class="badge badge-success">Disponible</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($ind['apres_midi']): ?>
                            <span class="badge badge-danger">Indisponible</span>
                            <?php else: ?>
                            <span class="badge badge-success">Disponible</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($ind['motif'] ?? '-') ?></td>
                        <td>
                            <button onclick="supprimerIndisponibilite(<?= $ind['id'] ?>)" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Ajout Indisponibilité -->
<div id="indispoModal" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeModal('indispoModal')"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fas fa-clock"></i> Ajouter une indisponibilité</h3>
            <button class="modal-close" onclick="closeModal('indispoModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="/enseignant/indisponibilites/ajouter" data-validate-form>
            <div class="modal-body">
                <div class="form-group">
                    <label for="date" class="required">Date</label>
                    <input type="date" id="date" name="date" class="form-control" 
                           data-validate="required" required>
                </div>
                
                <div class="form-group">
                    <label>Périodes</label>
                    <div class="form-check">
                        <input type="checkbox" id="matin" name="matin" value="1">
                        <label for="matin">Matin (8h-12h)</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="apres_midi" name="apres_midi" value="1">
                        <label for="apres_midi">Après-midi (14h-18h)</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="motif">Motif (optionnel)</label>
                    <input type="text" id="motif" name="motif" class="form-control" 
                           placeholder="Ex: Congé, Formation, etc.">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('indispoModal')">
                    Annuler
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function supprimerIndisponibilite(id) {
    if (!confirm('Supprimer cette indisponibilité ?')) return;
    
    fetch('/enseignant/indisponibilites/supprimer/' + id, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            location.reload();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(() => showToast('Erreur lors de la suppression', 'error'));
}
</script>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>