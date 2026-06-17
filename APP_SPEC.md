# APP SPEC (Application Specification) : Système de Gestion Numérique des Soutenances Universitaires

Ce document sert de référence fondamentale pour le projet, décrivant la vision, les objectifs, les fonctionnalités clés et les contraintes du Système de Gestion Numérique des Soutenances Universitaires.

## 1. Description du Produit (Problem Statement)

Actuellement, la gestion des soutenances universitaires (mémoires, thèses, PFE) est majoritairement manuelle ou fragmentée, entraînant des inefficacités, des erreurs de planification, des retards administratifs et des difficultés d'archivage. Ce projet vise à résoudre ces problèmes en offrant une solution numérique centralisée.

## 2. Utilisateurs Cibles et Personas

Le système s'adresse à cinq catégories d'utilisateurs, chacune avec des besoins et des droits d'accès spécifiques :

| Profil Utilisateur | Rôle Principal | Besoins Clés |
| :----------------- | :------------- | :----------- |
| **Administrateur** | Responsable technique du système | Gestion des comptes, configuration globale, supervision du système. |
| **Secrétaire Pédagogique** | Gestion opérationnelle des soutenances | Planification, composition des jurys, génération et suivi des documents officiels, archivage. |
| **Enseignant / Jury** | Participation aux soutenances | Consultation du planning, déclaration des disponibilités, saisie des notes et avis. |
| **Étudiant** | Candidat à la soutenance | Consultation de son dossier, téléchargement des convocations et résultats. |
| **Responsable Pédagogique** | Supervision et pilotage | Accès aux statistiques, rapports, validation des PV. |

## 3. Fonctionnalités Principales (MVP)

Les fonctionnalités clés du Minimum Viable Product (MVP) sont regroupées par module, avec une priorité "Essentiel" (P1) :

### 3.1. Module Planning & Soutenances (P1)
*   Création et gestion des dossiers de soutenance (étudiant, sujet, filière, type, directeur).
*   Association d'un créneau horaire, d'une salle et d'un statut à chaque soutenance.
*   Vérification automatique de la disponibilité des salles et détection des conflits.
*   Vues calendrier (mensuelle, hebdomadaire, journalière) avec filtres.
*   Export du planning en PDF et iCal.

### 3.2. Module Jurys (P1)
*   Composition assistée des jurys (président, directeur, rapporteur, membre).
*   Vérification des conflits d'horaire des membres du jury.
*   Gestion des disponibilités des enseignants.
*   Workflow de demande et de suivi des confirmations des membres du jury.

### 3.3. Module Procès-Verbaux (PV) (P1)
*   Saisie des résultats (note, mention, observations) après soutenance.
*   Génération automatique de PV pré-remplis au format PDF.
*   Workflow de validation des PV (Brouillon -> En validation -> Validé -> Signé -> Archivé).
*   Journalisation des actions sur les PV.

### 3.4. Module Archivage & Recherche (P1)
*   Stockage structuré des documents (PV, convocations, attestations).
*   Moteur de recherche multicritère (étudiant, titre, filière, date, mention, statut PV).
*   Export en masse des résultats (CSV/Excel).
*   Téléchargement individuel des documents.

## 4. User Flows et Parcours Utilisateurs

Les parcours utilisateurs critiques sont les suivants :

*   **UC-01 — Planifier une soutenance (Secrétaire Pédagogique) :** Connexion -> Accès module Soutenances -> Nouvelle soutenance -> Saisie infos -> Sélection date/heure/salle -> Enregistrement -> Notification.
*   **UC-02 — Composer et confirmer un jury (Secrétaire Pédagogique & Enseignant) :** (Secrétaire) Ouverture soutenance -> Onglet Jury -> Ajout membres -> Enregistrement -> (Enseignant) Réception email -> Confirmation/Déclinaison -> (Secrétaire) Suivi confirmations.
*   **UC-03 — Générer et valider un PV (Secrétaire Pédagogique & Responsable Pédagogique) :** (Secrétaire) Accès dossier -> Saisie résultats -> Génération PV -> Soumission validation -> (Responsable) Examen PV -> Validation/Refus -> (Secrétaire) Suivi statut -> Archivage PV -> Notification étudiant.

## 5. Contraintes Techniques

*   **Architecture :** Application web MVC à trois niveaux (Frontend, Backend API REST, Base de données relationnelle).
*   **Technologies Backend :** Laravel (PHP).
*   **Base de données :** MySQL 8.x.
*   **Authentification :** JWT + RBAC.
*   **Génération PDF :** Solution serveur (ex: PDFKit ou Puppeteer).
*   **Hébergement :** Serveur dans l'établissement ou cloud souverain.
*   **Performance :** Temps de chargement < 2s, support de 200 utilisateurs simultanés, génération PDF < 5s, recherche < 3s.
*   **Sécurité :** Authentification sécurisée, RBAC, HTTPS/TLS 1.3, journal d'audit complet.
*   **Compatibilité :** Navigateurs modernes, responsive (desktop, tablette, mobile).
*   **Accessibilité :** WCAG 2.1 niveau AA.

## 6. Critères de Succès

*   Réduction de 50% de la charge administrative liée à la gestion des soutenances.
*   Zéro erreur de planification (conflits de salles/jurys).
*   Taux de disponibilité du système de 99,5%.
*   Satisfaction utilisateur élevée (mesurée par sondages).
*   Conformité totale avec les obligations légales d'archivage (10 ans).
*   Déploiement du MVP dans les délais impartis (12 semaines - 16 semaines).
