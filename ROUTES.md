# Contrat des routes Backend — GestSoutenance

Ce document liste toutes les routes exposées par le Backend, à l'usage de l'équipe Frontend.
Généré à partir de `php artisan route:list` et des règles de validation réelles des contrôleurs.

## ⚠️ Point d'architecture important

Ce backend est une application **Laravel MVC classique avec sessions** (Breeze), **pas une API REST/JSON**.

- Toutes les routes ci-dessous utilisent les **sessions** et le **cookie CSRF** (`auth`, `verified`, `role:...`).
- Chaque action de mutation (`POST`/`PUT`/`DELETE`) répond par une **redirection HTTP 302** avec un message flash en session :
  - succès : `session('success')`
  - erreur métier : `session('error')`
  - erreurs de validation : `$errors` (MessageBag Laravel standard), avec les anciennes valeurs ré-injectées (`old()`)
- Il n'y a **pas de réponse JSON** sur ces routes. Si le Frontend a besoin de JSON (SPA, fetch/axios), il faut le demander explicitement — ce n'est pas encore prévu côté Backend.
- Toute requête `POST`/`PUT`/`PATCH`/`DELETE` doit inclure le token CSRF (`@csrf` en Blade, ou le header `X-CSRF-TOKEN` / `X-XSRF-TOKEN` si appel JS).
- Les messages de validation sont désormais en **français** (`lang/fr/validation.php`).

## Convention des réponses

| Cas | Comportement |
|---|---|
| Validation échouée | Redirection vers la page précédente + `$errors` en session |
| Action métier refusée (ex: confirmer une soutenance sans salle) | Redirection vers la page précédente + `session('error')` |
| Action réussie | Redirection vers une route nommée + `session('success')` |
| Accès refusé (mauvais rôle) | HTTP 403 |
| Non authentifié | Redirection vers `/login` |

---

## 1. Authentification (Breeze — standard, non documenté en détail ici)

| Méthode | URI | Nom | Description |
|---|---|---|---|
| GET | `/login` | `login` | Formulaire de connexion |
| POST | `/login` | — | `email`, `password` |
| POST | `/logout` | `logout` | — |
| GET | `/register` | `register` | Formulaire d'inscription |
| POST | `/register` | — | `name`, `email`, `password`, `password_confirmation` |
| GET\|POST | `/forgot-password` | `password.request` / `password.email` | `email` |
| GET\|POST | `/reset-password[/{token}]` | `password.reset` / `password.store` | `token`, `email`, `password`, `password_confirmation` |
| GET\|POST | `/confirm-password` | `password.confirm` | `password` |
| GET | `/verify-email` | `verification.notice` | — |
| GET | `/verify-email/{id}/{hash}` | `verification.verify` | — |
| POST | `/email/verification-notification` | `verification.send` | — |
| PUT | `/password` | `password.update` | `current_password`, `password`, `password_confirmation` |

## 2. Profil (tout utilisateur authentifié)

| Méthode | URI | Nom | Corps attendu |
|---|---|---|---|
| GET | `/profile` | `profile.edit` | — |
| PATCH | `/profile` | `profile.update` | `name`, `email` |
| DELETE | `/profile` | `profile.destroy` | `password` (bag `userDeletion`) |

## 3. Documents & Notifications (tout utilisateur authentifié)

| Méthode | URI | Nom | Corps attendu | Notes |
|---|---|---|---|---|
| GET | `/documents/{document}/download` | `documents.download` | — | 403 si l'utilisateur n'est ni l'étudiant concerné, ni un membre du jury, ni du staff (secrétaire/admin/responsable) |
| GET | `/notifications` | `notifications.index` | — | Liste paginée (15/page), notifications de l'utilisateur connecté |
| PUT | `/notifications/{notification}/read` | `notifications.read` | — | 403 si la notification n'appartient pas à l'utilisateur |

## 4. Admin (rôle `administrateur`)

| Méthode | URI | Nom | Corps attendu | Notes |
|---|---|---|---|---|
| GET | `/admin/dashboard` | `admin.dashboard` | — | |
| GET | `/admin/users` | `users.index` | — | Liste paginée (15/page) |
| GET | `/admin/users/create` | `users.create` | — | |
| POST | `/admin/users` | `users.store` | `name`, `email` (unique), `role` (`administrateur\|secretaire_pedagogique\|enseignant\|etudiant\|responsable_pedagogique`) | Mot de passe généré : `password123` |
| GET | `/admin/users/{user}` | `users.show` | — | |
| GET | `/admin/users/{user}/edit` | `users.edit` | — | |
| PUT\|PATCH | `/admin/users/{user}` | `users.update` | `name`, `email` (unique sauf soi-même), `role` | |
| DELETE | `/admin/users/{user}` | `users.destroy` | — | Refusé si l'utilisateur cible a le rôle `administrateur` |

## 5. Secrétaire pédagogique (rôles `secretaire_pedagogique`, `administrateur`)

### Soutenances

| Méthode | URI | Nom | Corps attendu |
|---|---|---|---|
| GET | `/secretaire/soutenances` | `soutenances.index` | — (liste paginée 15/page) |
| GET | `/secretaire/soutenances/create` | `soutenances.create` | — |
| POST | `/secretaire/soutenances` | `soutenances.store` | `etudiant_id`, `directeur_id`, `titre`, `filiere`, `type` (`licence\|master\|doctorat`), `date` (>today), `heure`, `salle_id?`, `statut?` |
| GET | `/secretaire/soutenances/{soutenance}` | `soutenances.show` | — |
| GET | `/secretaire/soutenances/{soutenance}/edit` | `soutenances.edit` | — |
| PUT\|PATCH | `/secretaire/soutenances/{soutenance}` | `soutenances.update` | mêmes champs que `store` (sauf `date` sans contrainte `after:today`) |
| DELETE | `/secretaire/soutenances/{soutenance}` | `soutenances.destroy` | — |
| PUT | `/secretaire/soutenances/{soutenance}/confirm` | `secretaire.soutenances.confirm` | — | Refusé (`session('error')`) si pas de salle ou pas de jury |
| PUT | `/secretaire/soutenances/{soutenance}/cancel` | `secretaire.soutenances.cancel` | — | Notifie l'étudiant et le jury |

### Jury

| Méthode | URI | Nom | Corps attendu |
|---|---|---|---|
| POST | `/secretaire/soutenances/{soutenance}/jury` | `secretaire.jury.store` | `utilisateur_id` (id enseignant), `role` (`president\|directeur\|rapporteur\|membre`) |
| DELETE | `/secretaire/jury/{jury}` | `secretaire.jury.destroy` | — |

### PV (procès-verbal)

| Méthode | URI | Nom | Corps attendu |
|---|---|---|---|
| POST | `/secretaire/soutenances/{soutenance}/pv` | `secretaire.pv.store` | `note` (0-20), `observations?` |
| PUT | `/secretaire/pv/{pv}` | `secretaire.pv.update` | `note` (0-20), `observations?` (refusé si `status = archive`) |
| PUT | `/secretaire/pv/{pv}/submit` | `secretaire.pv.submit` | — (refusé si `status != brouillon`) |
| GET | `/secretaire/pv/{pv}/pdf` | `secretaire.pv.pdf` | — | Renvoie un fichier PDF en téléchargement (pas de redirect) |

## 6. Enseignant (rôle `enseignant`)

| Méthode | URI | Nom | Corps attendu |
|---|---|---|---|
| GET | `/enseignant/dashboard` | `enseignant.dashboard` | — |
| PUT | `/enseignant/jury/{jury}/confirm` | `enseignant.jury.confirm` | — | 403 si ce n'est pas son propre siège de jury |
| PUT | `/enseignant/jury/{jury}/decline` | `enseignant.jury.decline` | — | idem |
| GET | `/enseignant/indisponibilites` | `enseignant.indisponibilites.index` | — | indisponibilités de l'enseignant connecté |
| POST | `/enseignant/indisponibilites` | `enseignant.indisponibilites.store` | `date_debut`, `date_fin` (≥ `date_debut`), `motif?` |
| PUT | `/enseignant/indisponibilites/{indisponibilite}` | `enseignant.indisponibilites.update` | idem (403 si pas le propriétaire) |
| DELETE | `/enseignant/indisponibilites/{indisponibilite}` | `enseignant.indisponibilites.destroy` | — (403 si pas le propriétaire) |

## 7. Responsable pédagogique (rôle `responsable_pedagogique`)

| Méthode | URI | Nom | Corps attendu |
|---|---|---|---|
| GET | `/responsable/dashboard` | `responsable.dashboard` | — |
| PUT | `/responsable/pv/{pv}/validate` | `responsable.pv.validate` | — (refusé si `status != en_validation`) |
| PUT | `/responsable/pv/{pv}/reject` | `responsable.pv.reject` | `commentaire` (obligatoire) | Repasse le PV en `brouillon` |

## 8. Étudiant (rôle `etudiant`)

| Méthode | URI | Nom |
|---|---|---|
| GET | `/etudiant/dashboard` | `etudiant.dashboard` |

---

## Valeurs d'énumération utilisées dans l'application

| Champ | Valeurs possibles |
|---|---|
| `users.role` | `administrateur`, `secretaire_pedagogique`, `enseignant`, `etudiant`, `responsable_pedagogique` |
| `soutenances.type` | `licence`, `master`, `doctorat` |
| `soutenances.statut` | `brouillon`, `planifiee`, `confirmee`, `realisee`, `annulee` |
| `jury_membres.role` | `president`, `directeur`, `rapporteur`, `membre` |
| `jury_membres.statut_confirmation` | `en_attente`, `confirme`, `refuse` |
| `pvs.status` | `brouillon`, `en_validation`, `valide`, `signe`, `archive` |
| `documents.type` | `pv`, `convocation`, `attestation` |

---

*Ce fichier est généré manuellement à partir des routes existantes au moment de l'écriture. Si une route change, mettez ce document à jour dans la même PR.*
