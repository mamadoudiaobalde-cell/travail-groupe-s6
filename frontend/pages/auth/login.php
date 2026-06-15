<?php
// frontend/pages/auth/login.php

// Inclure le bootstrap
require_once __DIR__ . '/../../../backend/includes/bootstrap.php';

// Vérifier les fonctions nécessaires (à ajouter dans bootstrap.php)
if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('dashboardForRole')) {
    function dashboardForRole($role) {
        $dashboards = [
            'administrateur' => '/travail-groupe-s6/frontend/pages/admin/dashboard.php',
            'secretaire_pedagogique' => '/travail-groupe-s6/frontend/pages/secretaire/dashboard.php',
            'enseignant' => '/travail-groupe-s6/frontend/pages/enseignant/dashboard.php',
            'etudiant' => '/travail-groupe-s6/frontend/pages/etudiant/dashboard.php',
            'responsable_pedagogique' => '/travail-groupe-s6/frontend/pages/responsable/dashboard.php'
        ];
        return $dashboards[$role] ?? '/travail-groupe-s6/frontend/pages/auth/login.php';
    }
}

if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

// Rediriger si déjà connecté
if (isLoggedIn()) {
    redirect(dashboardForRole($_SESSION['role']));
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if ($email === '' || $password === '') {
        $error = 'Veuillez remplir tous les champs.';
    } elseif (login($email, $password)) {
        redirect(dashboardForRole($_SESSION['role']));
    } else {
        $error = 'Email ou mot de passe incorrect.';
    }
}

$pageTitle = 'Connexion';
define('APP_NAME', 'GestSoutenance');

// Inclure le header (adapté pour la page de connexion)
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - <?= e(APP_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .auth-container {
            width: 100%;
            max-width: 450px;
        }
        
        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            padding: 40px;
            text-align: center;
        }
        
        .auth-card h1 {
            font-size: 28px;
            color: #2d3748;
            margin-bottom: 10px;
        }
        
        .subtitle {
            color: #718096;
            font-size: 14px;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #4a5568;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            cursor: pointer;
            border: none;
            font-size: 16px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 100%;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: left;
        }
        
        .alert-error {
            background: #fed7d7;
            color: #742a2a;
            border-left: 4px solid #f56565;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h1><?= e(APP_NAME) ?></h1>
            <p class="subtitle">Système de Gestion des Soutenances Universitaires</p>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>
            
            <?php if (isset($_GET['logout'])): ?>
                <div class="alert" style="background:#c6f6d5; color:#22543d; border-left-color:#48bb78;">
                    Vous avez été déconnecté avec succès.
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['expired'])): ?>
                <div class="alert" style="background:#bee3f8; color:#2c5282; border-left-color:#4299e1;">
                    Votre session a expiré. Veuillez vous reconnecter.
                </div>
            <?php endif; ?>
            
            <form method="POST" class="form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required 
                           value="<?= e($_POST['email'] ?? '') ?>" 
                           placeholder="exemple@univ.sn">
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-primary">Se connecter</button>
            </form>
        </div>
    </div>
</body>
</html>