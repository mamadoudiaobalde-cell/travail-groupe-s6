# INTEGRATION : Services Externes et Configurations

Ce document recense tous les services externes et APIs tierces que l'application de gestion des soutenances universitaires utilisera. Il détaille leurs configurations, les variables d'environnement nécessaires et les points d'intégration.

## 1. Services d'Envoi d'Emails (Notifications)

Le système enverra des emails pour les convocations, les rappels, les demandes de confirmation de jury et les notifications de résultats.

*   **Service :** SMTP (Simple Mail Transfer Protocol)
*   **Description :** Utilisation d'un serveur SMTP existant (celui de l'établissement ou un service tiers comme Mailgun, SendGrid).
*   **Configuration Laravel (`.env`) :**
    ```env
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io # ou votre hôte SMTP
    MAIL_PORT=2525 # ou votre port SMTP
    MAIL_USERNAME=null # ou votre nom d'utilisateur SMTP
    MAIL_PASSWORD=null # ou votre mot de passe SMTP
    MAIL_ENCRYPTION=null # ou tls/ssl
    MAIL_FROM_ADDRESS=
noreply@sgs.univ.com # Adresse email d'envoi
    MAIL_FROM_NAME="${APP_NAME}"
    ```
*   **Points d'intégration :** `app/Mail` pour les classes d'envoi d'emails, `app/Notifications` pour les notifications Laravel.
*   **Limites/Quotas :** Dépend du fournisseur SMTP. À surveiller pour éviter les blocages.

## 2. Génération de PDF (Procès-Verbaux, Convocations)

Le système générera des documents PDF dynamiquement (PV, convocations, attestations).

*   **Service :** `barryvdh/laravel-dompdf` ou `spatie/browsershot` (basé sur Puppeteer)
*   **Description :** Utilisation d'une librairie PHP pour générer des PDF à partir de vues HTML Laravel. `browsershot` est recommandé pour une meilleure fidélité de rendu HTML/CSS.
*   **Configuration Laravel (`composer.json`) :**
    ```json
    "require": {
        "barryvdh/laravel-dompdf": "^2.0" // ou "spatie/browsershot": "^3.0"
    }
    ```
*   **Points d'intégration :** Contrôleurs ou Services responsables de la génération des PV. Vues Blade pour les templates PDF.
*   **Dépendances :** Pour `browsershot`, nécessite Node.js et Puppeteer installés sur le serveur.

## 3. Authentification (JWT)

L'authentification des utilisateurs se fera via des JSON Web Tokens pour les API REST.

*   **Service :** `tymon/jwt-auth` (ou implémentation manuelle)
*   **Description :** Fournit une solution robuste pour l'authentification sans état des API.
*   **Configuration Laravel (`composer.json`) :**
    ```json
    "require": {
        "tymon/jwt-auth": "^1.0"
    }
    ```
*   **Configuration Laravel (`.env`) :**
    ```env
    JWT_SECRET=your_jwt_secret_key_here
    ```
    (Générer avec `php artisan jwt:secret`)
*   **Points d'intégration :** `app/Http/Controllers/AuthController`, `app/Http/Middleware/Authenticate`.

## 4. Stockage de Fichiers (Documents Archivés)

Les documents générés (PV, convocations) seront stockés de manière pérenne.

*   **Service :** Système de fichiers local (pour le MVP) ou S3 compatible (pour la production/scalabilité).
*   **Description :** Laravel Filesystem permet d'abstraire le stockage. Pour le MVP, un stockage local sur le serveur est suffisant. Pour la production, un service comme AWS S3 ou MinIO est préférable.
*   **Configuration Laravel (`.env`) :**
    ```env
    FILESYSTEM_DISK=local # ou s3
    
    # Si S3 est utilisé
    AWS_ACCESS_KEY_ID=your_access_key
    AWS_SECRET_ACCESS_KEY=your_secret_key
    AWS_DEFAULT_REGION=your_region
    AWS_BUCKET=your_bucket_name
    AWS_USE_PATH_STYLE_ENDPOINT=false
    ```
*   **Points d'intégration :** Services de gestion des documents (`DocumentService`), contrôleurs d'upload/téléchargement.

## 5. Base de Données (MySQL)

La base de données relationnelle pour le stockage de toutes les données de l'application.

*   **Service :** MySQL 8.x
*   **Description :** Base de données robuste et open-source, nativement supportée par Laravel. Disponible via WAMP, MAMP, XAMPP ou installation directe.
*   **Configuration Laravel (`.env`) :**
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=sgs_db
    DB_USERNAME=root        # ou l'utilisateur MySQL configuré localement
    DB_PASSWORD=            # mot de passe MySQL local (vide par défaut sur WAMP/XAMPP)
    ```
*   **Points d'intégration :** Modèles Eloquent, Migrations, Seeders.
*   **Note :** Créer la base de données manuellement avant de lancer les migrations : `CREATE DATABASE sgs_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`

Ce document sera mis à jour à chaque ajout ou modification d'un service externe.
