<?php
// Redirection vers le dashboard selon le rôle
if (isset($_SESSION['user_role'])) {
    $role = $_SESSION['user_role'];
    $redirects = [
        'admin' => '/admin/dashboard',
        'secretaire' => '/secretaire/dashboard',
        'enseignant' => '/enseignant/dashboard',
        'etudiant' => '/etudiant/dashboard',
        'responsable' => '/responsable/dashboard',
    ];
    redirect($redirects[$role] ?? '/dashboard');
} else {
    redirect('/login');
}
?>