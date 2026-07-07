# 🎓 GestSoutenance - Système de Gestion des Soutenances

**Stack :** HTML · CSS · JavaScript · PHP · MySQL

## 📋 Table des matières

- [Installation](#installation)
- [Connexion](#connexion)
- [Structure du projet](#structure-du-projet)
- [Fonctionnalités](#fonctionnalités)

---

## 🚀 Installation

1. **Cloner le repository**
   ```bash
   git clone https://github.com/mamadoudiaobalde-cell/travail-groupe-s6.git
   cd travail-groupe-s6
   ```

2. **Placer dans XAMPP**
   ```
   C:\xampp\htdocs\travail-groupe-s6
   ```

3. **Configurer la base de données**
   - Démarrer XAMPP (Apache + MySQL)
   - Ouvrir phpMyAdmin : http://localhost/phpmyadmin
   - Importer `backend/database/schema.sql`
   - Importer `backend/database/seed.sql`

4. **Configurer .env**
   - Dupliquer `.env.example` → `.env`
   - Configurer vos identifiants MySQL

5. **Accéder à l'application**
   ```
   http://localhost/travail-groupe-s6
   ```

---

## 🔐 Connexion

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Admin | admin@univ.sn | password |
| Secrétaire | secretaire@univ.sn | password |
| Enseignant | prof@univ.sn | password |
| Étudiant | etudiant@univ.sn | password |
| Responsable | responsable@univ.sn | password |

---

## 📁 Structure du Projet

```
travail-groupe-s6/
├── backend/                    ← API & Logique métier
│   ├── config/                 → database.php, app.php
│   ├── includes/               → auth.php, audit.php, fonctions.php
│   ├── models/                 → User, Salle, Soutenance, Jury, Document, Pv
│   ├── controllers/            → Auth, Admin, Secrétaire, Enseignant, Étudiant, Responsable
│   ├── services/               → Mail, PDF, Export, Audit
│   ├── database/               → schema.sql, seed.sql
│   └── index.php               → Point d'entrée backend
│
├── frontend/                   ← Interface utilisateur
│   ├── assets/
│   │   ├── css/                → main, admin, dashboard, forms, tables, responsive, print
│   │   ├── js/                 → main, auth, charts, validation, filters, modals, notifications, datatable, calendar
│   │   ├── images/             → logo, favicon, bg-login, empty-state, success
│   │   ├── fonts/              → inter-regular, inter-bold, inter-semibold
│   │   └── uploads/            → convocations, pv, attestations, rapports, temp
│   ├── templates/              → header, footer, navbar, sidebar, modal, alerts, pagination
│   ├── components/
│   │   ├── cards/              → stat-card, soutenance-card, user-card
│   │   ├── forms/              → login-form, user-form, salle-form, soutenance-form, pv-form, indispo-form
│   │   ├── tables/             → user-table, salle-table, soutenance-table, jury-table
│   │   └── badges/             → status-badge, role-badge, mention-badge
│   └── pages/
│       ├── auth/               → login, logout, changer-mdp, dashboard
│       ├── admin/              → dashboard, utilisateurs, salles, audit, config
│       ├── secretaire/         → dashboard, soutenances, convocations, rapports
│       ├── enseignant/         → dashboard, mes-soutenances, participations, jury-confirm, indisponibilites, pv-saisie
│       ├── etudiant/           → dashboard, ma-soutenance, convocation, resultats, pv-download, attestation, historique
│       ├── responsable/        → dashboard, statistiques, exports, alertes, rapports, promotions
│       └── errors/             → 403, 404, 500
│
├── index.php                   → Point d'entrée principal
├── .env                        → Variables d'environnement
├── .env.example                → Template .env
├── .htaccess                   → Configuration Apache
└── README.md                   → Ce fichier
```

---

## ✨ Fonctionnalités

### 👨‍💼 Administrateur
- ✅ Gestion des utilisateurs (CRUD)
- ✅ Gestion des salles (CRUD)
- ✅ Consultation des logs d'audit
- ✅ Configuration système

### 👩‍💻 Secrétaire
- ✅ Planification des soutenances
- ✅ Génération des convocations
- ✅ Gestion des rapports
- ✅ Suivi des soutenances

### 👨‍🏫 Enseignant
- ✅ Consultation de mes soutenances
- ✅ Confirmation de participation jury
- ✅ Déclaration d'indisponibilités
- ✅ Saisie des procès-verbaux

### 🎓 Étudiant
- ✅ Consultation de ma soutenance
- ✅ Téléchargement de la convocation
- ✅ Consultation des résultats
- ✅ Téléchargement du PV et attestation

### 📊 Responsable
- ✅ Statistiques détaillées
- ✅ Exports (CSV/PDF)
- ✅ Alertes à traiter
- ✅ Rapports personnalisés

---

## 🔒 Sécurité

- ✅ Protection `.htaccess` Apache
- ✅ Backend non accessible directement
- ✅ Templates protégés
- ✅ Fichier `.env` dénié
- ✅ Uploads sécurisés (pas d'exécution PHP)
- ✅ Headers de sécurité configurés
- ✅ Audit des actions utilisateurs

---

## 👥 Rôles et Permissions

| Action | Admin | Secrétaire | Enseignant | Étudiant | Responsable |
|--------|-------|-----------|-----------|----------|------------|
| Gérer utilisateurs | ✅ | ❌ | ❌ | ❌ | ❌ |
| Gérer salles | ✅ | ❌ | ❌ | ❌ | ❌ |
| Planifier soutenances | ❌ | ✅ | ❌ | ❌ | ❌ |
| Générer convocations | ❌ | ✅ | ❌ | ❌ | ❌ |
| Saisir PV | ❌ | ❌ | ✅ | ❌ | ❌ |
| Voir résultats | ❌ | ❌ | ❌ | ✅ | ✅ |
| Voir statistiques | ❌ | ❌ | ❌ | ❌ | ✅ |

---

## 📞 Support

Pour toute question ou problème, veuillez contacter l'administrateur système.

**Auteur :** Abdoulaye Kande  
**Email :** abdoulaye.kande2@unchk.edu.sn  
**Date :** 15 Juin 2026  
**Université :** UNCHK

---

**Projet académique - Travail de Groupe S6**
# 🎓 Gestion des Soutenances - GestSoutenance

## 📖 Présentation du Projet

**GestSoutenance** est une application web développée pour **l'Université   UNCHK** dans le cadre d'un projet de fin d'études.

L'objectif principal est de **numériser et d'optimiser la gestion des soutenances universitaires** en remplaçant les processus manuels (papiers, emails, tableaux Excel) par une plateforme centralisée, sécurisée et accessible en ligne.

---

## 🎯 Objectifs du Projet

- **Centraliser** toutes les informations liées aux soutenances (étudiants, jurys, salles, dates)
- **Simplifier** la planification et l'organisation des soutenances
- **Automatiser** la génération des documents officiels (convocation, PV, attestation)
- **Faciliter** la communication entre les différents acteurs (étudiants, enseignants, secrétariat)
- **Sécuriser** l'accès aux données avec un système d'authentification par rôle
- **Fournir** des tableaux de bord statistiques pour le suivi et l'analyse

---

## 👥 Acteurs du Système

L'application gère **5 rôles** distincts avec des fonctionnalités adaptées :

| Rôle | Responsabilités |
|------|-----------------|
| **Administrateur** | Gestion des utilisateurs, des salles, configuration du système |
| **Secrétaire Pédagogique** | Planification des soutenances, affectation des salles, envoi des convocations |
| **Enseignant / Jury** | Consultation des soutenances, confirmation de participation, saisie des notes |
| **Étudiant** | Consultation de sa soutenance, téléchargement de la convocation, consultation des résultats |
| **Responsable Pédagogique** | Supervision, statistiques, validation des PV, exports de données |

---

## 🛠️ Technologies Utilisées

### Backend (PHP & Laravel)

| Technologie | Description |
|-------------|-------------|
| **PHP 8.2.12** | Langage de programmation côté serveur |
| **Laravel 12.62.0** | Framework PHP moderne (MVC, ORM, Sécurité) |
| **MySQL** | Système de gestion de base de données relationnelle |
| **Eloquent ORM** | Gestion des bases de données avec modèles |
| **Laravel Breeze** | Authentification (login, register, password reset) |
| **Middleware** | Vérification des rôles et des permissions |
| **Blade** | Moteur de templates pour les vues |

### Frontend

| Technologie | Description |
|-------------|-------------|
| **Blade** | Templating PHP intégré à Laravel |
| **Bootstrap 5** | Framework CSS pour l'interface responsive |
| **JavaScript** | Interactions dynamiques (Chart.js) |
| **FontAwesome** | Icônes vectorielles |
| **Chart.js** | Graphiques statistiques |

### Outils et Services

| Technologie | Description |
|-------------|-------------|
| **DomPDF** | Génération de documents PDF (convocation, PV, attestation) |
| **PHPMailer** | Envoi d'emails (convocations, notifications) |
| **Git** | Versionnement du code source |
| **GitHub** | Hébergement du code et collaboration |

---

## 📋 Fonctionnalités Implémentées

### Module Authentification
- ✅ Inscription et connexion sécurisée
- ✅ Gestion des sessions
- ✅ Vérification des rôles
- ✅ Changement de mot de passe

### Module Administration
- ✅ CRUD Utilisateurs
- ✅ CRUD Salles
- ✅ Audit des actions
- ✅ Gestion des permissions

### Module Secrétariat
- ✅ Planification des soutenances
- ✅ Affectation des salles
- ✅ Détection des conflits de planning
- ✅ Confirmation des soutenances

### Module Enseignant
- ✅ Consultation des soutenances dirigées
- ✅ Confirmation de participation au jury
- ✅ Saisie des notes (PV)
- ✅ Déclaration des indisponibilités

### Module Étudiant
- ✅ Consultation de la soutenance
- ✅ Téléchargement de la convocation
- ✅ Consultation des résultats
- ✅ Téléchargement du PV et de l'attestation

### Module Responsable
- ✅ Tableaux de bord statistiques
- ✅ Graphiques par filière et par type
- ✅ Export CSV/PDF des données

---

## 🚀 Installation

### Prérequis

- PHP >= 8.0
- MySQL
- Composer
- Node.js & NPM (optionnel pour les assets)

### Étapes d'installation

```bash
# 1. Cloner le dépôt
git clone https://github.com/mamadoudiaobalde-cell/travail-groupe-s6.git
cd travail-groupe-s6

# 2. Aller sur la branche de développement
git checkout layebara-tech

# 3. Installer les dépendances PHP
composer install

# 4. Configurer l'environnement
cp .env.example .env

# 5. Générer la clé de l'application
php artisan key:generate

# 6. Configurer la base de données dans .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=gest_soutenance
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Créer la base de données
# CREATE DATABASE gest_soutenance;

# 8. Lancer les migrations
php artisan migrate

# 9. Démarrer le serveur
php artisan serve
