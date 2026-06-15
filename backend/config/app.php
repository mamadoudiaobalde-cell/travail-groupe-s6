<?php

define('APP_NAME', 'GestSoutenance');
define('APP_URL', 'http://localhost/travail-groupe-s6');
define('SESSION_TIMEOUT', 8 * 3600);

define('ROLES', [
    'administrateur'          => 'Administrateur',
    'secretaire'              => 'Secrétaire pédagogique',
    'enseignant'              => 'Enseignant / Jury',
    'etudiant'                => 'Étudiant',
    'responsable_pedagogique' => 'Responsable pédagogique',
]);

define('ROLE_DASHBOARDS', [
    'administrateur'          => '/pages/admin/dashboard.php',
    'secretaire'              => '/pages/secretaire/dashboard.php',
    'enseignant'              => '/pages/enseignant/dashboard.php',
    'etudiant'                => '/pages/etudiant/dashboard.php',
    'responsable_pedagogique' => '/pages/responsable/dashboard.php',
]);
