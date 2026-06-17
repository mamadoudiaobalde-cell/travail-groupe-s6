# FEATURE BACKLOG : Système de Gestion Numérique des Soutenances Universitaires

Ce document liste toutes les fonctionnalités identifiées pour le projet, classées par priorité (P0, P1, P2) et détaillées par User Stories et Critères d'Acceptation. Il servira de base pour la planification des sprints sur Trello.

## 1. Priorités

*   **P0 (Critique) :** Fonctionnalités absolument essentielles pour le fonctionnement minimal du système (MVP). Sans elles, le système n'a pas de valeur. Doivent être réalisées en premier.
*   **P1 (Important) :** Fonctionnalités importantes qui ajoutent une valeur significative au système et améliorent l'expérience utilisateur. À inclure dès que le P0 est stable.
*   **P2 (Souhaitable) :** Fonctionnalités secondaires ou d'amélioration qui peuvent être ajoutées si le temps et les ressources le permettent, ou dans des phases ultérieures.

## 2. Backlog des Fonctionnalités

### 2.1. Module Authentification & Utilisateurs

| Priorité | User Story | Critères d'Acceptation | Estimation (Effort) |
| :------- | :--------- | :--------------------- | :------------------ |
| **P0** | En tant qu'utilisateur, je peux me connecter/déconnecter au système avec mes identifiants pour accéder à mes fonctionnalités. | - L'utilisateur peut saisir son email et mot de passe.<br>- Le système authentifie l'utilisateur et génère un token JWT.<br>- L'utilisateur est redirigé vers son tableau de bord.<br>- L'utilisateur peut se déconnecter et le token est invalidé. | 3 jours |
| **P0** | En tant qu'administrateur, je peux gérer les rôles et permissions des utilisateurs pour contrôler l'accès aux fonctionnalités. | - L'administrateur peut assigner un rôle (Admin, Secrétaire, Enseignant, Étudiant, Responsable) à un utilisateur.<br>- Les utilisateurs n'ont accès qu'aux fonctionnalités correspondant à leur rôle. | 2 jours |
| **P1** | En tant qu'utilisateur, je peux consulter et modifier les informations de mon profil (sauf rôle) pour maintenir mes données à jour. | - L'utilisateur peut voir son prénom, nom, email.<br>- L'utilisateur peut modifier son prénom, nom.<br>- L'utilisateur ne peut pas modifier son email ou son rôle. | 1 jour |
| **P1** | En tant qu'enseignant, je peux déclarer mes indisponibilités pour que le système en tienne compte lors de la composition des jurys. | - L'enseignant peut ajouter/modifier/supprimer des plages d'indisponibilité.<br>- Le système utilise ces indisponibilités lors de la suggestion de jurys. | 1 jour |

### 2.2. Module Planning et Soutenances

| Priorité | User Story | Critères d'Acceptation | Estimation (Effort) |
| :------- | :--------- | :--------------------- | :------------------ |
| **P0** | En tant que secrétaire, je peux créer une nouvelle soutenance en associant un étudiant, un sujet, une filière, un type et un directeur de mémoire. | - Le formulaire de création de soutenance est disponible.<br>- Tous les champs obligatoires sont présents et validés.<br>- Une soutenance est créée avec un statut 'Brouillon'. | 2 jours |
| **P0** | En tant que secrétaire, je peux assigner une date, une heure et une salle à une soutenance, et le système vérifie les conflits. | - Le système propose les salles disponibles pour le créneau choisi.<br>- En cas de conflit (salle déjà occupée), une alerte est affichée.<br>- La soutenance est mise à jour avec la date, heure et salle. | 2 jours |
| **P0** | En tant qu'utilisateur, je peux consulter le calendrier des soutenances avec des vues mensuelles, hebdomadaires et journalières. | - Le calendrier affiche les soutenances planifiées.<br>- Des filtres par filière, salle, statut sont disponibles.<br>- Les vues (mois, semaine, jour) sont fonctionnelles. | 3 jours |
| **P1** | En tant que secrétaire, je peux exporter le planning des soutenances au format PDF et iCal. | - Un bouton d'export PDF est disponible et génère un document lisible.<br>- Un bouton d'export iCal est disponible et génère un fichier compatible avec les agendas. | 1 jour |

### 2.3. Module Jurys

| Priorité | User Story | Critères d'Acceptation | Estimation (Effort) |
| :------- | :--------- | :--------------------- | :------------------ |
| **P0** | En tant que secrétaire, je peux composer un jury pour une soutenance en assignant des rôles (Président, Rapporteur, Membre) aux enseignants. | - L'interface de composition du jury permet d'ajouter des enseignants.<br>- Les rôles peuvent être attribués à chaque membre.<br>- Le système vérifie les conflits d'horaire des enseignants ajoutés. | 2 jours |
| **P0** | En tant que secrétaire, je peux envoyer des demandes de confirmation aux membres du jury et suivre leur statut. | - Un email de demande de confirmation est envoyé aux membres.<br>- Le statut de confirmation (en attente, confirmé, refusé) est visible.<br>- Des relances automatiques sont envoyées après 48h sans réponse. | 2 jours |

### 2.4. Module Procès-Verbaux (PV)

| Priorité | User Story | Critères d'Acceptation | Estimation (Effort) |
| :------- | :--------- | :--------------------- | :------------------ |
| **P0** | En tant que secrétaire, je peux saisir les résultats (note, mention, observations) d'une soutenance. | - Un formulaire de saisie des résultats est disponible après la soutenance.<br>- Les champs note, mention, observations sont enregistrés. | 1 jour |
| **P0** | En tant que secrétaire, je peux générer automatiquement un PV pré-rempli au format PDF à partir des résultats saisis. | - Un bouton de génération de PV est disponible.<br>- Le PV généré contient toutes les informations de la soutenance et les résultats.<br>- Le PV est au format PDF et respecte le modèle paramétrable. | 2 jours |
| **P0** | En tant que responsable pédagogique, je peux valider ou refuser un PV soumis par la secrétaire. | - Le responsable reçoit une notification de PV en attente de validation.<br>- Il peut consulter le PV et choisir de le valider ou de le refuser.<br>- En cas de refus, un commentaire est requis. | 1 jour |
| **P0** | En tant que secrétaire, je peux suivre le workflow de validation d'un PV (Brouillon, En validation, Validé, Signé, Archivé). | - Le statut du PV est clairement affiché.<br>- Les transitions entre les statuts sont gérées par le système.<br>- Un PV archivé est immuable. | 1 jour |

### 2.5. Module Archivage et Recherche

| Priorité | User Story | Critères d'Acceptation | Estimation (Effort) |
| :------- | :--------- | :--------------------- | :------------------ |
| **P0** | En tant qu'utilisateur, je peux rechercher des soutenances et des PV archivés via un moteur de recherche multicritère. | - Le formulaire de recherche avancée permet de filtrer par étudiant, titre, filière, date, mention, statut PV.<br>- Les résultats sont affichés dans un tableau paginé et triable. | 2 jours |
| **P1** | En tant que secrétaire, je peux exporter en masse les résultats d'une promotion au format CSV/Excel. | - Un bouton d'export est disponible sur la page de recherche.<br>- Le fichier exporté contient les données pertinentes des soutenances. | 1 jour |
| **P1** | En tant qu'utilisateur, je peux télécharger individuellement un PV ou une attestation. | - Un bouton de téléchargement est disponible pour chaque document.<br>- Le document est téléchargé au format original (PDF). | 0.5 jour |

### 2.6. Module Notifications et Statistiques

| Priorité | User Story | Critères d'Acceptation | Estimation (Effort) |
| :------- | :--------- | :--------------------- | :------------------ |
| **P1** | En tant qu'utilisateur, je reçois des notifications automatiques par email pour les événements importants (création soutenance, rappel J-7, J-3, J-1, disponibilité résultat). | - Les emails sont envoyés automatiquement aux parties concernées.<br>- Le contenu des emails est pertinent et informatif. | 2 jours |
| **P2** | En tant que responsable pédagogique, je peux consulter un tableau de bord avec des statistiques clés sur les soutenances. | - Le tableau de bord affiche des graphiques (ex: nombre de soutenances par mois, taux de réussite).<br>- Les indicateurs clés sont mis à jour en temps réel. | 2 jours |

## 3. Tâches Techniques Transversales

| Priorité | Tâche Technique | Critères d'Acceptation | Estimation (Effort) |
| :------- | :-------------- | :--------------------- | :------------------ |
| **P0** | Mise en place de l'environnement de développement local (PHP/MySQL/Laravel). | - Le projet peut être lancé via `php artisan serve`.<br>- La base de données MySQL est configurée et les migrations s'exécutent correctement.<br>- Les tests PHPUnit peuvent être lancés. | 2 jours |
| **P0** | Configuration de l'authentification JWT et du RBAC. | - Les endpoints de login/logout fonctionnent.<br>- Les routes sont protégées par des middlewares de rôle.<br>- Les utilisateurs peuvent accéder uniquement aux ressources autorisées. | 2 jours |
| **P1** | Implémentation de la couche Service/Repository/DTO. | - Les services contiennent la logique métier.<br>- Les repositories gèrent l'accès aux données.<br>- Les DTOs sont utilisés pour les requêtes/réponses API. | 3 jours |
| **P1** | Mise en place des tests unitaires et fonctionnels. | - Les tests couvrent les fonctionnalités critiques.<br>- Les tests s'exécutent sans erreur. | 2 jours |
| **P1** | Intégration d'un service d'envoi d'emails (ex: Mailtrap pour dev, SMTP pour prod). | - Les emails sont envoyés et reçus correctement. | 1 jour |
| **P2** | Mise en place de l'Audit Log pour les actions critiques. | - Toutes les actions CREATE/UPDATE/DELETE sont tracées.<br>- Les logs contiennent l'utilisateur, l'action, l'entité et les valeurs modifiées. | 1 jour |

Ce backlog est un document vivant et sera affiné et priorisé au fur et à mesure de l'avancement du projet et des retours de l'équipe.
