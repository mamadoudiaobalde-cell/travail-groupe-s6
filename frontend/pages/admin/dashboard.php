<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
requireRole(['administrateur']);
$pageTitle = 'Administration';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header"><div><h1>Administration</h1><p class="lead">Gestion du système</p></div></div>
<div class="cards">
    <a href="<?= e(APP_URL) ?>/pages/admin/salles/index.php" class="card card-link">
        <h3>Salles</h3><p>CRUD des salles de soutenance</p><span class="badge badge-active">Disponible</span>
    </a>
    <div class="card"><h3>Utilisateurs</h3><p>À développer</p><span class="badge badge-soon">Sprint 1</span></div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
