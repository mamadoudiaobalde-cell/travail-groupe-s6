<?php
require_once __DIR__ . '/../../../includes/bootstrap.php';
require_once __DIR__ . '/../../../includes/models/Salle.php';
requireRole(['administrateur']);

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$salle = Salle::find($id);
if (!$salle) { flash('error','Salle introuvable.'); redirect('/pages/admin/salles/index.php'); }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf($_POST['csrf_token'] ?? '')) { flash('error','Token invalide.'); redirect('/pages/admin/salles/edit.php?id='.$id); }
    $salle = ['id'=>$id,'nom'=>trim($_POST['nom']??''),'capacite'=>$_POST['capacite']??'','localisation'=>trim($_POST['localisation']??''),'equipements'=>trim($_POST['equipements']??''),'actif'=>(int)($_POST['actif']??1)];
    $errors = Salle::validate($salle, $id);
    if (!$errors) { Salle::update($id, $salle); flash('success','Salle modifiée.'); redirect('/pages/admin/salles/index.php'); }
}

$pageTitle = 'Modifier la salle';
require_once __DIR__ . '/../../../includes/header.php';
?>
<div class="page-header"><div><h1>Modifier la salle</h1><p class="lead"><?= e($salle['nom']) ?></p></div></div>
<?php require __DIR__.'/_form.php'; ?>
<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
