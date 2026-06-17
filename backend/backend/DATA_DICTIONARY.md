# DATA DICTIONARY : Système de Gestion Numérique des Soutenances Universitaires

Ce document définit les entités de données de l'application, leurs champs, types, contraintes et relations, servant de base pour la création des migrations et modèles Laravel. La base de données cible est **MySQL 8.x** ; tous les types listés ci-dessous sont nativement supportés par MySQL (y compris `JSON`, `ENUM`, et `DECIMAL`).

## 1. Entités Principales

Les entités principales du système sont : `User`, `Role`, `Defense` (Soutenance), `Room` (Salle), `Jury`, `JuryMember` (Membre du Jury), `Report` (PV), `Document`, et `AuditLog`.

## 2. Détail des Entités

### 2.1. User (Utilisateurs)
Représente tous les acteurs du système (Administrateur, Secrétaire, Enseignant, Étudiant, Responsable).

| Champ | Type | Contraintes | Description |
| :---- | :--- | :---------- | :---------- |
| `id` | UUID/BigInt | Primary Key | Identifiant unique de l'utilisateur. |
| `first_name` | String | Not Null | Prénom de l'utilisateur. |
| `last_name` | String | Not Null | Nom de famille de l'utilisateur. |
| `email` | String | Not Null, Unique | Adresse email institutionnelle. |
| `password` | String | Not Null | Mot de passe haché (bcrypt). |
| `role_id` | UUID/BigInt | Foreign Key | Référence au rôle de l'utilisateur. |
| `is_active` | Boolean | Default: true | Statut d'activation du compte. |
| `created_at` | Timestamp | | Date de création. |
| `updated_at` | Timestamp | | Date de dernière modification. |

### 2.2. Role (Rôles)
Définit les profils d'accès (RBAC).

| Champ | Type | Contraintes | Description |
| :---- | :--- | :---------- | :---------- |
| `id` | UUID/BigInt | Primary Key | Identifiant unique du rôle. |
| `name` | String | Not Null, Unique | Nom du rôle (ex: admin, secretary, teacher, student, manager). |
| `description` | Text | Nullable | Description du rôle. |

### 2.3. Defense (Soutenances)
Représente une soutenance planifiée ou réalisée.

| Champ | Type | Contraintes | Description |
| :---- | :--- | :---------- | :---------- |
| `id` | UUID/BigInt | Primary Key | Identifiant unique de la soutenance. |
| `student_id` | UUID/BigInt | Foreign Key | Référence à l'étudiant (User). |
| `title` | String | Not Null | Titre du mémoire/thèse. |
| `type` | Enum | Not Null | Type (Licence, Master, Doctorat). |
| `field_of_study` | String | Not Null | Filière de l'étudiant. |
| `scheduled_date` | Date | Nullable | Date prévue de la soutenance. |
| `start_time` | Time | Nullable | Heure de début. |
| `end_time` | Time | Nullable | Heure de fin estimée. |
| `room_id` | UUID/BigInt | Foreign Key, Nullable | Référence à la salle. |
| `status` | Enum | Default: 'draft' | Statut (Brouillon, Planifiée, Confirmée, Réalisée, Annulée). |
| `created_at` | Timestamp | | Date de création. |
| `updated_at` | Timestamp | | Date de dernière modification. |

### 2.4. Room (Salles)
Salles disponibles pour les soutenances.

| Champ | Type | Contraintes | Description |
| :---- | :--- | :---------- | :---------- |
| `id` | UUID/BigInt | Primary Key | Identifiant unique de la salle. |
| `name` | String | Not Null, Unique | Nom ou numéro de la salle. |
| `capacity` | Integer | Nullable | Capacité d'accueil. |
| `location` | String | Nullable | Bâtiment ou localisation. |
| `equipment` | Text | Nullable | Équipements disponibles (ex: projecteur). |

### 2.5. Jury (Jurys)
Regroupe les membres assignés à une soutenance.

| Champ | Type | Contraintes | Description |
| :---- | :--- | :---------- | :---------- |
| `id` | UUID/BigInt | Primary Key | Identifiant unique du jury. |
| `defense_id` | UUID/BigInt | Foreign Key, Unique | Référence à la soutenance. |

### 2.6. JuryMember (Membres du Jury)
Table de liaison entre Jury et User, avec le rôle spécifique pour la soutenance.

| Champ | Type | Contraintes | Description |
| :---- | :--- | :---------- | :---------- |
| `id` | UUID/BigInt | Primary Key | Identifiant unique de l'assignation. |
| `jury_id` | UUID/BigInt | Foreign Key | Référence au jury. |
| `user_id` | UUID/BigInt | Foreign Key | Référence à l'enseignant (User). |
| `role` | Enum | Not Null | Rôle dans le jury (Président, Directeur, Rapporteur, Membre). |
| `confirmation_status` | Enum | Default: 'pending' | Statut de confirmation (En attente, Confirmé, Refusé). |

### 2.7. Report (Procès-Verbaux - PV)
Résultats officiels de la soutenance.

| Champ | Type | Contraintes | Description |
| :---- | :--- | :---------- | :---------- |
| `id` | UUID/BigInt | Primary Key | Identifiant unique du PV. |
| `defense_id` | UUID/BigInt | Foreign Key, Unique | Référence à la soutenance. |
| `global_grade` | Decimal | Nullable | Note globale attribuée. |
| `mention` | String | Nullable | Mention obtenue. |
| `observations` | Text | Nullable | Commentaires du jury. |
| `status` | Enum | Default: 'draft' | Statut du PV (Brouillon, En validation, Validé, Signé, Archivé). |
| `signed_at` | Timestamp | Nullable | Date de signature finale. |
| `created_at` | Timestamp | | Date de création. |
| `updated_at` | Timestamp | | Date de dernière modification. |

### 2.8. Document (Documents)
Fichiers générés ou uploadés (PV PDF, convocations).

| Champ | Type | Contraintes | Description |
| :---- | :--- | :---------- | :---------- |
| `id` | UUID/BigInt | Primary Key | Identifiant unique du document. |
| `defense_id` | UUID/BigInt | Foreign Key | Référence à la soutenance associée. |
| `type` | Enum | Not Null | Type de document (PV, Convocation, Attestation). |
| `file_path` | String | Not Null | Chemin de stockage du fichier. |
| `file_hash` | String | Not Null | Hash SHA-256 pour garantir l'intégrité. |
| `created_at` | Timestamp | | Date de création. |

### 2.9. AuditLog (Journal d'Audit)
Traçabilité des actions critiques.

| Champ | Type | Contraintes | Description |
| :---- | :--- | :---------- | :---------- |
| `id` | UUID/BigInt | Primary Key | Identifiant unique du log. |
| `user_id` | UUID/BigInt | Foreign Key, Nullable | Utilisateur ayant effectué l'action. |
| `action` | String | Not Null | Type d'action (CREATE, UPDATE, DELETE, EXPORT). |
| `entity_type` | String | Not Null | Type d'entité concernée (ex: Defense, Report). |
| `entity_id` | UUID/BigInt | Nullable | ID de l'entité concernée. |
| `old_values` | JSON | Nullable | Valeurs avant modification. |
| `new_values` | JSON | Nullable | Valeurs après modification. |
| `ip_address` | String | Nullable | Adresse IP de l'utilisateur. |
| `created_at` | Timestamp | | Date de l'action. |

## 3. Relations (Eloquent ORM)

*   **User** `belongsTo` **Role**
*   **User** `hasMany` **Defense** (en tant qu'étudiant)
*   **User** `hasMany` **JuryMember** (en tant qu'enseignant)
*   **Defense** `belongsTo` **User** (étudiant)
*   **Defense** `belongsTo` **Room**
*   **Defense** `hasOne` **Jury**
*   **Defense** `hasOne` **Report**
*   **Defense** `hasMany` **Document**
*   **Jury** `belongsTo` **Defense**
*   **Jury** `hasMany` **JuryMember**
*   **JuryMember** `belongsTo` **Jury**
*   **JuryMember** `belongsTo` **User**
*   **Report** `belongsTo` **Defense**
*   **Document** `belongsTo` **Defense**
*   **AuditLog** `belongsTo` **User**

## 4. Règles de Validation Clés

*   `Defense.scheduled_date` doit être dans le futur lors de la création.
*   `Defense.end_time` doit être postérieur à `Defense.start_time`.
*   Un `User` (enseignant) ne peut pas avoir deux `JuryMember` avec des horaires de `Defense` qui se chevauchent.
*   Une `Room` ne peut pas être assignée à deux `Defense` avec des horaires qui se chevauchent.
*   Un `Report` ne peut être modifié que si son `status` n'est pas 'Archivé'.
