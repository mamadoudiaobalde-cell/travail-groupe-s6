# SYNC_WORKFLOW : Synchronisation du Travail de l'Équipe Backend

Ce document définit les règles et les outils pour assurer une collaboration fluide et une synchronisation efficace entre les membres de l'équipe Backend.

## 1. Gestion des Versions (Git)

Nous utilisons Git pour la gestion du code source. Le dépôt central est hébergé sur [Lien vers le dépôt GitHub/GitLab].

### 1.1. Stratégie de Branches
Nous suivons le modèle **GitHub Flow** :
*   **`main` :** Branche stable, contient le code prêt pour la production. Aucun commit direct n'est autorisé.
*   **`feature/[nom-de-la-feature]` :** Branche pour le développement d'une nouvelle fonctionnalité.
*   **`bugfix/[nom-du-bug]` :** Branche pour la correction d'un bug.
*   **`hotfix/[nom-du-fix]` :** Branche pour une correction urgente en production.

### 1.2. Processus de Pull Request (PR)
1.  Créer une branche à partir de `main`.
2.  Développer la fonctionnalité/correction.
3.  Pousser la branche sur le dépôt distant.
4.  Ouvrir une Pull Request vers `main`.
5.  **Revue de Code :** Au moins un autre membre de l'équipe (ou le Lead Dev) doit relire et valider la PR.
6.  **Tests :** Les tests automatisés doivent passer avec succès.
7.  Fusionner la PR dans `main` après validation.

## 2. Communication et Suivi des Tâches

### 2.1. Trello
*   Le tableau Trello est l'unique source de vérité pour l'état des tâches.
*   Chaque carte doit être assignée à une personne.
*   Mettre à jour le statut de la carte (À Faire, En Cours, Revue, Terminé) en temps réel.

### 2.2. Daily Stand-up
*   Réunion de 15 min chaque jour à [Heure] sur [Lien Discord/Meet].
*   Partage de l'avancement, des objectifs du jour et des blocages.

### 2.3. Discord / Slack
*   Canal `#backend` pour les discussions techniques.
*   Canal `#notifications` pour les alertes de CI/CD et les nouvelles PR.

## 3. Standards de Développement

*   **Linting :** Exécuter `php-cs-fixer` avant chaque commit.
*   **Tests :** Aucun code ne doit être fusionné sans tests unitaires/fonctionnels associés.
*   **Documentation :** Mettre à jour `CLAUDE.MD` et les autres documents de référence si nécessaire.

## 4. Workflow de Synchronisation des Données

*   **Migrations :** Toujours créer une migration pour toute modification de la structure de la base de données. Ne jamais modifier la BDD manuellement.
*   **Seeders :** Utiliser les seeders pour partager des données de test communes entre les développeurs.
*   **Fichiers `.env` :** Partager les nouvelles variables d'environnement nécessaires sur le canal de communication sécurisé (ne jamais committer le `.env`).

En suivant ces règles, nous garantissons une base de code propre, stable et une équipe synchronisée.
