<?php
require_once __DIR__ . '/../../../includes/bootstrap.php';
require_once __DIR__ . '/../../../includes/models/Salle.php';
requireRole(['administrateur']);

$errors = [];
$salle = ['nom'=>'','capacite'=>'','localisation'=>'','equipements'=>'','actif'=>1];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf($_POST['csrf_token'] ?? '')) { flash('error','Token invalide.'); redirect('/pages/admin/salles/create.php'); }
    $salle = ['nom'=>trim($_POST['nom']??''),'capacite'=>$_POST['capacite']??'','localisation'=>trim($_POST['localisation']??''),'equipements'=>trim($_POST['equipements']??''),'actif'=>(int)($_POST['actif']??1)];
    $errors = Salle::validate($salle);
    if (!$errors) { Salle::create($salle); flash('success','Salle créée.'); redirect('/pages/admin/salles/index.php'); }
}

$pageTitle = 'Nouvelle salle';
require_once __DIR__ . '/../../../includes/header.php';
?>
<div class="page-header"><div><h1>Nouvelle salle</h1></div></div>
<?php require __DIR__.'/_form.php'; ?>
<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
