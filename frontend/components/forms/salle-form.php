<?php $isEdit = !empty($salle['id']); ?>
<div class="form-card">
<form method="POST" class="form">
    <?= csrfField() ?>
    <div class="form-group">
        <label for="nom">Nom <span class="required">*</span></label>
        <input type="text" id="nom" name="nom" required maxlength="100" value="<?= e($salle['nom'] ?? '') ?>" class="<?= isset($errors['nom']) ? 'is-invalid' : '' ?>">
        <?php if (isset($errors['nom'])): ?><span class="error-text"><?= e($errors['nom']) ?></span><?php endif; ?>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="capacite">Capacité <span class="required">*</span></label>
            <input type="number" id="capacite" name="capacite" required min="1" max="1000" value="<?= e((string)($salle['capacite'] ?? '')) ?>" class="<?= isset($errors['capacite']) ? 'is-invalid' : '' ?>">
            <?php if (isset($errors['capacite'])): ?><span class="error-text"><?= e($errors['capacite']) ?></span><?php endif; ?>
        </div>
        <div class="form-group">
            <label for="actif">Statut</label>
            <select id="actif" name="actif">
                <option value="1" <?= ($salle['actif'] ?? 1) ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= !($salle['actif'] ?? 1) ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="localisation">Localisation</label>
        <input type="text" id="localisation" name="localisation" maxlength="200" value="<?= e($salle['localisation'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label for="equipements">Équipements</label>
        <textarea id="equipements" name="equipements" rows="3"><?= e($salle['equipements'] ?? '') ?></textarea>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Enregistrer' : 'Créer la salle' ?></button>
        <a href="index.php" class="btn btn-outline-dark">Annuler</a>
    </div>
</form>
</div>
