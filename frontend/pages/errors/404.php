<?php
$pageTitle = 'Page non trouvée';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - <?= $_ENV['APP_NAME'] ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="error-page">
    <div class="error-container">
        <div class="error-card">
            <div class="error-icon">
                <i class="fas fa-search"></i>
            </div>
            <h1>404</h1>
            <h2>Page non trouvée</h2>
            <p>La page que vous recherchez n'existe pas ou a été déplacée.</p>
            <div class="error-actions">
                <a href="/dashboard" class="btn btn-primary">
                    <i class="fas fa-home"></i> Retour à l'accueil
                </a>
                <a href="javascript:history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </div>
</body>
</html>

<style>
.error-page {
    background: var(--bg-color);
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    margin: 0;
}

.error-container {
    width: 100%;
    max-width: 500px;
    padding: 20px;
}

.error-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 40px;
    text-align: center;
    box-shadow: var(--shadow-hover);
}

.error-icon {
    font-size: 64px;
    color: var(--primary-color);
    margin-bottom: 20px;
}

.error-card h1 {
    font-size: 72px;
    margin: 0;
    color: var(--text-color);
    font-weight: 700;
}

.error-card h2 {
    font-size: 24px;
    color: var(--text-color);
    margin: 10px 0;
}

.error-card p {
    color: var(--text-light);
    margin-bottom: 30px;
}

.error-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
}
</style>