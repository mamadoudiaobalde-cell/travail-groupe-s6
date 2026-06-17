# Système de Gestion Numérique des Soutenances Universitaires — Backend

Backend API REST développé en **Laravel (PHP 8.4)** avec **MySQL 8.x**, pour la gestion centralisée et automatisée du cycle de vie des soutenances universitaires (planification, jurys, procès-verbaux, archivage).

---

## Prérequis

- PHP >= 8.4 (avec les extensions `pdo_mysql`, `mbstring`, `openssl`, `xml`, `curl`)
- MySQL >= 8.0
- Composer >= 2.x
- Un environnement local : WAMP, MAMP, XAMPP ou PHP/MySQL installés directement

---

## Installation

```bash
# 1. Cloner le dépôt
git clone <url-du-repo>
cd system_gestion_soutenances_backend

# 2. Installer les dépendances PHP
composer install

# 3. Copier et configurer l'environnement
cp .env.example .env

# 4. Générer la clé d'application
php artisan key:generate
```

### Configuration de la base de données

Créer la base de données dans MySQL avant de lancer les migrations :

```sql
CREATE DATABASE sgs_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Puis renseigner les identifiants dans `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sgs_db
DB_USERNAME=root
DB_PASSWORD=
```

### Migrations et données initiales

```bash
# Exécuter les migrations
php artisan migrate

# (Optionnel) Peupler la base avec des données de test
php artisan db:seed
```

### Authentification JWT

```bash
# Générer le secret JWT
php artisan jwt:secret
```

---

## Lancer le serveur de développement

```bash
php artisan serve
```

L'API est accessible sur `http://localhost:8000`.

---

## Commandes utiles

| Commande | Description |
|---|---|
| `php artisan serve` | Démarre le serveur de développement |
| `php artisan migrate` | Exécute les migrations |
| `php artisan migrate:fresh --seed` | Recrée la BDD et insère les seeders |
| `php artisan test` | Lance la suite de tests PHPUnit |
| `php artisan route:list` | Liste toutes les routes API |
| `php artisan jwt:secret` | Génère le secret JWT |

---

## Architecture

Le projet suit une architecture en couches (Controller → Service → Repository) avec les principes SOLID et un DDD léger.

```
app/
├── Http/
│   ├── Controllers/    # Réception des requêtes HTTP
│   ├── Requests/       # Validation des données entrantes
│   └── Resources/      # Transformation des réponses JSON
├── Services/           # Logique métier
├── Repositories/       # Accès aux données (abstraction Eloquent)
├── Models/             # Modèles Eloquent
└── DTOs/               # Objets de transfert de données
```

## Documentation

| Document | Description |
|---|---|
| [CLAUDE.MD](CLAUDE.MD) | Conventions, stack et commandes du projet |
| [APP_SPEC.md](APP_SPEC.md) | Spécifications fonctionnelles |
| [ARCHITECTURE_TECHNIQUE.md](ARCHITECTURE_TECHNIQUE.md) | Architecture et composants |
| [DATA_DICTIONARY.md](DATA_DICTIONARY.md) | Dictionnaire des données et entités |
| [INTEGRATION.md](INTEGRATION.md) | Services externes et configuration |
| [FEATURE_BACKLOG.md](FEATURE_BACKLOG.md) | Backlog des fonctionnalités |

---

## Tests

```bash
php artisan test
```

Les tests utilisent PHPUnit et couvrent les services, repositories et contrôleurs.

---

## Licence

Usage interne — projet universitaire.
