# CLAUDE.MD : Contexte et Conventions du Projet Backend Laravel

Ce document sert de briefing pour toute nouvelle personne rejoignant l'équipe. Il fournit un aperçu du projet, de son stack technique, des conventions de développement et des bonnes pratiques à suivre pour le backend.

## 1. Description du Projet

Le projet est un **Système de Gestion Numérique des Soutenances Universitaires**. Son objectif principal est de centraliser et d'automatiser la gestion du cycle de vie des soutenances (planification, jurys, procès-verbaux, archivage) au sein des établissements universitaires. Il vise à réduire la charge administrative, à améliorer la traçabilité des processus et à sécuriser l'accès aux données.

**Fonctionnalités Clés (Backend) :**
*   **Gestion des Utilisateurs et Rôles (RBAC) :** Authentification, autorisation, gestion des profils.
*   **Planification des Soutenances :** Création, modification, suppression, vérification des disponibilités (salles, jurys).
*   **Gestion des Jurys :** Composition, suivi des confirmations, gestion des indisponibilités des enseignants.
*   **Génération et Validation des PV :** Saisie des résultats, génération de PDF, workflow de validation.
*   **Archivage et Recherche :** Stockage structuré des documents, moteur de recherche multicritère, exports.
*   **Notifications :** Envoi automatique d'emails pour les événements clés.

## 2. Stack Technique et Commandes

### 2.1. Technologies Clés
*   **Framework Backend :** Laravel (PHP 8.4)
*   **Base de Données :** MySQL 8.x
*   **Authentification :** JWT (JSON Web Tokens) pour les API REST, avec gestion des rôles (RBAC).
*   **Serveur Web local :** WAMP, MAMP, XAMPP ou PHP/MySQL installés directement (pas de Docker).
*   **Gestionnaire de Paquets PHP :** Composer
*   **Tests :** PHPUnit

### 2.2. Commandes Essentielles

Prérequis : avoir PHP 8.4, MySQL 8.x et Composer disponibles en local (via WAMP/MAMP/XAMPP ou installation directe).

Pour installer les dépendances PHP :
```bash
composer install
```

Pour copier le fichier d'environnement et configurer la connexion MySQL :
```bash
cp .env.example .env
# Éditer .env : renseigner DB_DATABASE, DB_USERNAME, DB_PASSWORD
```

Pour générer une clé d'application Laravel :
```bash
php artisan key:generate
```

Pour exécuter les migrations de base de données :
```bash
php artisan migrate
```

Pour lancer le serveur de développement local :
```bash
php artisan serve
```

Pour exécuter les tests :
```bash
php artisan test
```

## 3. Conventions et Règles

### 3.1. Architecture et Structure des Dossiers
Nous suivons une architecture basée sur les principes SOLID et le Domain-Driven Design (DDD) léger, en séparant les préoccupations.

*   **`app/Http/Controllers` :** Contrôleurs légers, responsables de la réception des requêtes et de l'appel aux services.
*   **`app/Services` :** Contient la logique métier complexe. Chaque service doit être dédié à une entité ou un domaine fonctionnel (ex: `UserService`, `DefenseService`).
*   **`app/Repositories` :** Abstraction de la couche de persistance des données. Chaque repository gère les opérations CRUD pour une entité spécifique (ex: `UserRepository`, `DefenseRepository`).
*   **`app/DTOs` (Data Transfer Objects) :** Objets pour transférer des données entre les couches (Controller, Service, Repository) de manière structurée et validée. À utiliser pour les requêtes entrantes et les réponses sortantes.
*   **`app/Models` :** Modèles Eloquent pour interagir avec la base de données. Contiennent les relations et les accesseurs/mutateurs.
*   **`app/Http/Requests` :** Classes de validation des requêtes HTTP.
*   **`app/Http/Resources` :** Classes pour transformer les modèles Eloquent en formats de réponse API (JSON).

### 3.2. Style de Code
*   Respecter la norme PSR-12.
*   Utiliser `php-cs-fixer` pour maintenir la cohérence du style.
*   Les noms de variables, fonctions et classes doivent être explicites et en anglais (sauf pour les données métier qui peuvent être en français si nécessaire, mais les champs de BDD et les variables de code restent en anglais).

### 3.3. Naming Conventions
*   **Classes :** `PascalCase` (ex: `UserService`, `DefenseController`).
*   **Méthodes/Fonctions :** `camelCase` (ex: `createUser`, `getDefenseById`).
*   **Variables :** `camelCase` (ex: `userId`, `defenseStatus`).
*   **Tables de BDD :** `snake_case` au pluriel (ex: `users`, `defenses`).
*   **Colonnes de BDD :** `snake_case` au singulier (ex: `first_name`, `scheduled_date`).
*   **Migrations :** `YYYY_MM_DD_HHMMSS_create_table_name_table.php`.

### 3.4. Gestion des Erreurs
*   Utiliser les exceptions Laravel pour gérer les erreurs. Créer des exceptions personnalisées pour les erreurs métier.
*   Les API doivent retourner des réponses JSON standardisées en cas d'erreur (code HTTP approprié, message d'erreur clair).

### 3.5. Tests
*   Chaque fonctionnalité majeure doit être couverte par des tests unitaires et/ou fonctionnels (Feature Tests).
*   Utiliser PHPUnit. Créer des tests pour les services, les repositories et les contrôleurs.

### 3.6. Documentation
*   Commenter le code complexe.
*   Mettre à jour la documentation (APP_SPEC, DATA_DICTIONARY, etc.) à chaque évolution majeure.
*   Utiliser les docblocks PHPDoc pour les classes et méthodes.

Ce fichier est un document vivant et sera mis à jour au fur et à mesure de l'avancement du projet et des décisions de l'équipe.
