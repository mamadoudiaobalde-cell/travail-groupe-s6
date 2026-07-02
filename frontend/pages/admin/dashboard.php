<?php
session_start();
$page_title = 'Dashboard Admin';
require_once '../../templates/header.php';
require_once '../../templates/navbar.php';
?>

<div class="container mt-4">
    <h1>Dashboard Administrateur</h1>
    <p>Bienvenue <?= $_SESSION['user']['name'] ?? 'Admin' ?> !</p>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body text-center">
                    <h2>0</h2>
                    <p>Utilisateurs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <h2>0</h2>
                    <p>Salles</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body text-center">
                    <h2>0</h2>
                    <p>Soutenances</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body text-center">
                    <h2>0</h2>
                    <p>Ce mois</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../templates/footer.php'; ?>