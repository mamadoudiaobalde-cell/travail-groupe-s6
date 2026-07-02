<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="form-container">
    <h2><i class="fas fa-plus-circle"></i> Nouvelle soutenance</h2>
    <p class="text-muted">Remplissez le formulaire pour planifier une nouvelle soutenance.</p>
    
    <?php include_component('forms/soutenance-form', [
        'action' => '/secretaire/soutenances/creer',
        'etudiants' => $etudiants,
        'salles' => $salles
    ]); ?>
</div>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>