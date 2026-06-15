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
**Université :** Cheikh Anta Diop de Dakar

---

**Projet académique - Travail de Groupe S6**