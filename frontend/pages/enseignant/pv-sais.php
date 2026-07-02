<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="form-container">
    <h2><i class="fas fa-file-alt"></i> Saisie du PV</h2>
    <p class="text-muted">Saisissez les résultats de la soutenance.</p>
    
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-body">
            <div class="detail-row">
                <label>Soutenance :</label>
                <p><strong><?= htmlspecialchars($soutenance['titre']) ?></strong></p>
            </div>
            <div class="detail-row">
                <label>Étudiant :</label>
                <p><?= htmlspecialchars($soutenance['etudiant_prenom'] ?? '') ?> <?= htmlspecialchars($soutenance['etudiant_nom'] ?? '') ?></p>
            </div>
            <div class="detail-row">
                <label>Date :</label>
                <p><?= formatDate($soutenance['date_heure']) ?></p>
            </div>
        </div>
    </div>
    
    <form method="POST" action="/enseignant/soutenances/pv/<?= $soutenance['id'] ?>" data-validate-form>
        <div class="form-group">
            <label for="note" class="required">Note sur 20</label>
            <input type="number" id="note" name="note" class="form-control" 
                   step="0.5" min="0" max="20" 
                   value="<?= $pv['note'] ?? '' ?>"
                   data-validate="required|min_value:0|max_value:20" required>
            <div class="form-error" id="note_error"></div>
            <small class="form-help">Note de 0 à 20, avec des demi-points possibles.</small>
        </div>
        
        <div class="form-group">
            <label for="mention">Mention (calculée automatiquement)</label>
            <input type="text" id="mention" name="mention" class="form-control" 
                   value="<?= isset($pv['mention']) ? getMentionLabel($pv['mention']) : '' ?>" readonly disabled>
            <small class="form-help">La mention est calculée automatiquement en fonction de la note.</small>
        </div>
        
        <div class="form-group">
            <label for="commentaire">Commentaire</label>
            <textarea id="commentaire" name="commentaire" class="form-control" rows="4"><?= htmlspecialchars($pv['commentaire'] ?? '') ?></textarea>
            <small class="form-help">Commentaires sur la prestation de l'étudiant.</small>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer le PV
            </button>
            <?php if (isset($pv) && $pv['statut'] === 'brouillon'): ?>
            <button type="submit" name="action" value="soumettre" class="btn btn-success">
                <i class="fas fa-paper-plane"></i> Soumettre pour validation
            </button>
            <?php endif; ?>
            <a href="/enseignant/mes-soutenances" class="btn btn-secondary">
                <i class="fas fa-times"></i> Annuler
            </a>
        </div>
    </form>
</div>

<script>
// Calcul automatique de la mention
document.getElementById('note').addEventListener('input', function() {
    const note = parseFloat(this.value);
    const mentionInput = document.getElementById('mention');
    
    if (isNaN(note)) {
        mentionInput.value = '';
        return;
    }
    
    let mention = '';
    if (note >= 18) mention = 'Excellent';
    else if (note >= 16) mention = 'Très Bien';
    else if (note >= 14) mention = 'Bien';
    else if (note >= 12) mention = 'Assez Bien';
    else if (note >= 10) mention = 'Passable';
    else mention = 'Insuffisant';
    
    mentionInput.value = mention;
});
</script>

<style>
.detail-row {
    display: flex;
    padding: 6px 0;
    border-bottom: 1px solid #f5f5f5;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-row label {
    width: 120px;
    font-weight: 600;
    color: var(--text-light);
    flex-shrink: 0;
}

.detail-row p {
    flex: 1;
    margin: 0;
}
</style>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>