# JOURNAL D'ERREUR : Suivi des Bugs et Résolutions

Ce document est un journal vivant destiné à tracer tous les bugs rencontrés durant le développement et la maintenance du projet. Pour chaque entrée, il doit inclure une description du problème, le message d'erreur exact, la cause identifiée, la solution appliquée et le statut.

## Structure d'une Entrée

Chaque entrée du journal doit suivre le format suivant :

```markdown
### [ID du Bug] - [Titre court du problème]

**Date :** [AAAA-MM-JJ]
**Signalé par :** [Nom de la personne ou du système]
**Module concerné :** [Ex: Authentification, Planning, API Jurys]
**Priorité :** [Ex: Critique, Majeure, Mineure]

**Description du Problème :**
[Description détaillée du comportement inattendu, des étapes pour reproduire le bug.]

**Message d'Erreur Exact (si applicable) :**
```
[Copier/coller le message d'erreur complet, stack trace, logs, etc.]
```

**Cause Identifiée :**
[Explication de la racine du problème (erreur de logique, faute de frappe, mauvaise configuration, dépendance externe, etc.).]

**Solution Appliquée :**
[Description des actions prises pour corriger le bug (ex: modification de fichier X, ajout de validation Y, mise à jour de dépendance Z). Inclure les références aux commits ou Pull Requests si possible.]

**Statut :** [Ouvert / En Cours / Résolu / Fermé]
**Date de Résolution :** [AAAA-MM-JJ (si résolu)]
**Résolu par :** [Nom de la personne]
```

## Exemples d'Entrées

### BUG-001 - Erreur 500 lors de la création d'une soutenance sans salle

**Date :** 2026-06-17
**Signalé par :** Secrétaire Pédagogique (Test Fonctionnel)
**Module concerné :** Planning & Soutenances (Backend)
**Priorité :** Majeure

**Description du Problème :**
Lorsqu'une secrétaire tente de créer une nouvelle soutenance via l'API sans spécifier de `room_id`, l'API retourne une erreur 500 au lieu d'une erreur de validation 422. Le champ `room_id` est défini comme nullable dans le modèle, mais la logique de vérification des conflits de salle attend une valeur non nulle.

**Message d'Erreur Exact (si applicable) :**
```
Illuminate\Database\QueryException: SQLSTATE[23502]: Not null violation: 7 ERROR:  null value in column "room_id" violates not-null constraint (SQL: insert into "defenses" ...)
```

**Cause Identifiée :**
La migration de la table `defenses` avait initialement `room_id` comme `nullable()`, mais une contrainte `NOT NULL` a été ajoutée ultérieurement au niveau de la base de données sans mise à jour correspondante dans le modèle Eloquent ou la validation de la requête. De plus, la logique de `DefenseService` pour la vérification des conflits de salle ne gérait pas le cas où `room_id` est null.

**Solution Appliquée :**
1.  Mise à jour de la règle de validation dans `StoreDefenseRequest` pour rendre `room_id` obligatoire.
2.  Modification de la méthode `checkRoomAvailability` dans `DefenseService` pour s'assurer qu'elle est appelée uniquement si `room_id` est présent.
3.  Ajout d'un test unitaire pour ce scénario.

**Statut :** Résolu
**Date de Résolution :** 2026-06-18
**Résolu par :** Backend Dev 2

### BUG-002 - Token JWT expiré ne déconnecte pas l'utilisateur Frontend

**Date :** 2026-06-19
**Signalé par :** Frontend Dev 1
**Module concerné :** Authentification (Frontend/Backend)
**Priorité :** Majeure

**Description du Problème :**
Après l'expiration du token JWT (8 heures d'inactivité), l'utilisateur n'est pas automatiquement déconnecté du frontend. Les requêtes API subséquentes échouent avec une erreur 401, mais l'interface utilisateur reste connectée, nécessitant un rafraîchissement manuel ou une tentative d'action pour révéler l'état déconnecté.

**Message d'Erreur Exact (si applicable) :**
(Côté Frontend, dans la console du navigateur)
```
GET https://api.sgs.univ.com/api/user 401 (Unauthorized)
```
(Côté Backend, dans les logs Laravel)
```
Tymon\JWTAuth\Exceptions\TokenExpiredException: Token has expired
```

**Cause Identifiée :**
Le frontend ne vérifie pas proactivement l'expiration du token. Il attend une réponse 401 du backend pour déclencher la déconnexion. La logique de gestion des erreurs 401 n'était pas correctement implémentée pour vider le token localement et rediriger vers la page de connexion.

**Solution Appliquée :**
1.  Implémentation d'un intercepteur HTTP côté frontend pour détecter les réponses 401.
2.  Lors d'une réponse 401, le token JWT est supprimé du local storage et l'utilisateur est redirigé vers la page de connexion.
3.  Ajout d'une notification utilisateur pour informer de la déconnexion automatique.

**Statut :** Résolu
**Date de Résolution :** 2026-06-20
**Résolu par :** Frontend Dev 1 (avec assistance Lead Dev)

Ce journal doit être mis à jour systématiquement pour chaque bug rencontré, afin de faciliter le débogage futur et d'améliorer la qualité du code.
