# LifeBridge — site public et gestion sécurisée des utilisateurs

LifeBridge est une plateforme PHP de sensibilisation au don de sang et au don d'organes. Cette version conserve les pages publiques du site (Accueil, Don de sang, Don d'organes, Campagnes, Demandes et Contact) et ajoute l'inscription, la connexion, les espaces par rôle, la gestion du profil personnel pour les citoyens et agents de santé, ainsi qu'un backoffice de gestion des utilisateurs.

## 1. Installation avec XAMPP

1. Décompresser le dossier `LifeBridge` dans `C:\xampp\htdocs\`.
2. Démarrer **Apache** et **MySQL** depuis XAMPP.
3. Ouvrir `http://localhost/phpmyadmin`.
4. Importer le fichier `database.sql`. Il crée la base `lifebridge` et la table `users`.
5. Vérifier les identifiants MySQL dans `config/database.php` (par défaut : utilisateur `root`, mot de passe vide sous XAMPP local).
6. Ouvrir le site : `http://localhost/LifeBridge/` ou `http://localhost/LifeBridge/index.php?page=home`.

Le backoffice est accessible à un utilisateur ayant le rôle `securite` : `http://localhost/LifeBridge/admin/index.php?page=dashboard`.

## 2. Comptes et rôles

| Rôle | Accès | Code requis à l'inscription |
|---|---|---|
| `citoyen` | Espace Citoyen | Aucun |
| `agent_sante` | Espace Agent de santé | `SANTE-2026` |
| `securite` | Backoffice Administration | `ADMIN-2026` |

Les codes sont des codes de démonstration. Changez-les dans `config/config.php` avant de présenter ou partager le projet. Pour un vrai déploiement, la création des comptes administrateurs devrait être réservée à un administrateur déjà autorisé.

## 3. Fonctionnement général

1. Le navigateur demande une page à `index.php`.
2. `index.php` démarre la session et charge les en-têtes de sécurité.
3. Le contrôleur demandé traite l'action (inscription, connexion, espace ou page publique).
4. `UserService.php` utilise PDO pour communiquer avec la table `users`.
5. Une connexion valide crée une session contenant l'identifiant, le nom et le rôle.
6. `AccessController.php` vérifie les droits avant d'afficher un espace protégé.
7. Les citoyens et agents de santé peuvent ouvrir `Mon profil` pour modifier leur nom, leur e-mail et éventuellement leur mot de passe. Ils peuvent aussi supprimer leur propre compte après avoir confirmé leur mot de passe.
8. Le backoffice permet au rôle `securite` d'ajouter, consulter, modifier et supprimer les utilisateurs.

## 4. Architecture MVC et CRUD

- **Model** : représente les données. `Models/User.php` contient les attributs privés et les getters/setters.
- **View** : affiche les pages HTML. Exemple : `admin/app/Views/pages/users_list.php`.
- **Controller** : reçoit la demande, appelle les traitements et choisit la vue. Exemple : `admin/app/Controllers/UsersController.php`.
- **Service** : `Controllers/UserService.php` contient les opérations PDO sur les utilisateurs.

CRUD signifie : Create (INSERT), Read (SELECT), Update (UPDATE) et Delete (DELETE).

## 5. Répartition officielle proposée pour les six membres

| Membre | Responsabilité | Fichiers principaux | Ce que le membre doit savoir expliquer |
|---|---|---|---|
| **Hadded Eya** | Authentification | `Controllers/AuthController.php`, `Views/connexion.php` | Formulaire POST, inscription, connexion, `password_hash()`, `password_verify()`, validation du jeton CSRF |
| **Krida Hiba** | Sessions | `config/session.php` | `session_start()`, `$_SESSION`, cookie PHPSESSID, régénération d'identifiant, expiration après 30 minutes, déconnexion |
| **Lajnef Fatma** | CRUD utilisateurs | `Models/User.php`, `Controllers/UserService.php`, `admin/app/Controllers/UsersController.php`, `admin/app/Views/pages/users_list.php`, `user_form.php` | Classe, objet, attributs privés, getters/setters, PDO, requêtes préparées, `fetch()`, `fetchAll()`, INSERT/SELECT/UPDATE/DELETE |
| **Ben Rabiha Fattouma** | Rôles, espaces et profil personnel | `Controllers/AccessController.php`, `Controllers/EspaceController.php`, `Views/espace-citoyen.php`, `Views/espace-sante.php`, `Views/profil.php`, `admin/index.php` | Authentification vs autorisation, rôle en session, redirection, HTTP 403, modification du profil, suppression du compte avec mot de passe |
| **Belhaj Sghaier Youssef** | Validation des formulaires | `Controllers/Validator.php`, `Views/assets/js/validation.js`, formulaires d'inscription et d'administration | `if`, fonctions de validation, `filter_var()`, `preg_match()`, validation JavaScript et PHP, contrôle d'e-mail doublon côté PHP |
| **Allouche Mohamed Salah** | Base de données et sécurité | `config/database.php`, `config/security.php`, `config/config.php`, `database.sql`, `README.md` | PDO, connexion MySQL, injection SQL, `prepare()`/`execute()`, XSS, `htmlspecialchars()`, CSRF, `hash_equals()`, en-têtes HTTP |

Cette répartition attribue une responsabilité principale à chaque membre. Les fichiers partagés (par exemple `UserService.php` utilisé par l'authentification et l'administration) doivent être expliqués ensemble lorsque les deux parties les utilisent.

## 6. Présentation orale : phrase de chaque membre

### Hadded Eya — Authentification

« Je me suis occupée de l'authentification. J'ai développé l'inscription et la connexion dans `AuthController`. À l'inscription, je vérifie les informations, je contrôle que l'e-mail n'existe pas déjà et je vérifie le code si la personne demande un rôle privilégié. Le mot de passe est haché avec `password_hash()` avant d'être enregistré. À la connexion, `password_verify()` compare le mot de passe saisi avec le hachage stocké. »

À montrer : `register()`, `login()` et les deux formulaires dans `connexion.php`.

### Krida Hiba — Sessions

« Je me suis occupée des sessions. Une session permet au site de reconnaître le même utilisateur lorsqu'il passe d'une page à une autre. Après la connexion, nous stockons son identifiant, son nom et son rôle dans `$_SESSION`. J'ai configuré les cookies avec HttpOnly et SameSite, renouvelé l'identifiant après connexion et ajouté une expiration après 30 minutes d'inactivité. »

À montrer : `startSecureSession()`, `loginSession()` et `destroySession()`.

### Lajnef Fatma — CRUD utilisateurs

« Je me suis occupée de la gestion des utilisateurs. La classe `User` représente un utilisateur avec ses attributs privés et ses getters/setters. `UserService` contient les requêtes PDO pour ajouter, afficher, modifier et supprimer les comptes. Le contrôleur `UsersController` reçoit les actions de l'administrateur et utilise les vues pour afficher la liste et les formulaires. »

À montrer : la classe `User`, `getAll()`, `insert()`, `update()`, `delete()` et le `foreach` dans la liste.

### Ben Rabiha Fattouma — Rôles et backoffice

« Je me suis occupée des rôles, des espaces et du profil personnel. LifeBridge possède trois rôles : Citoyen, Agent de santé et Sécurité. Après la connexion, chaque rôle est dirigé vers son espace. Les citoyens et les agents de santé peuvent ouvrir Mon profil pour modifier leur nom, leur e-mail ou leur mot de passe. Ils peuvent aussi supprimer leur compte en confirmant leur mot de passe. La page ne permet pas de modifier le rôle, afin qu'un utilisateur ne puisse pas devenir administrateur lui-même. `AccessController` vérifie les permissions côté serveur. »

À montrer : `homeUrl()`, `requireRole()`, `EspaceController::profil()`, `Views/profil.php` et la vérification dans `admin/index.php`.

### Belhaj Sghaier Youssef — Validation

« Je me suis occupé de la validation des formulaires. JavaScript vérifie les champs avant l'envoi pour aider l'utilisateur. PHP vérifie de nouveau les données côté serveur, car JavaScript peut être désactivé. Nous contrôlons le nom, le format de l'e-mail et la longueur et la confirmation du mot de passe. Les doublons d'e-mail sont recherchés en PHP avec une requête SQL. »

À montrer : `Validator.php`, `validation.js` et un test avec une adresse invalide.

### Allouche Mohamed Salah — Base de données et sécurité

« Je me suis occupé de la connexion à la base de données et de la sécurité. La classe `Database` centralise la connexion PDO. Les requêtes préparées protègent contre les injections SQL. La fonction `e()` utilise `htmlspecialchars()` pour éviter l'exécution de code HTML ou JavaScript injecté. La classe `Csrf` génère et vérifie un jeton sur les formulaires sensibles. J'ai également configuré les en-têtes HTTP et préparé la base de données et la documentation. »

À montrer : `Database::getConnexion()`, `e()`, `Csrf::token()`, `Csrf::verify()` et `database.sql`. Les formulaires de modification et de suppression du profil utilisent également un jeton CSRF.

## 7. Protections de sécurité

| Risque | Protection | Emplacement |
|---|---|---|
| Injection SQL | Requêtes préparées `prepare()` et `execute()` | `Controllers/UserService.php` |
| Mot de passe lisible en base | `password_hash()` et `password_verify()` | `Controllers/AuthController.php`, `UsersController.php` |
| XSS | `htmlspecialchars()` via `e()` | `config/security.php` et vues |
| CSRF | Jeton aléatoire vérifié avec `hash_equals()` | `config/security.php` et formulaires POST |
| Fixation de session | `session_regenerate_id(true)` | `config/session.php` |
| Lecture du cookie par JavaScript | Cookie `HttpOnly` | `config/session.php` |
| Requête intersite | Cookie `SameSite=Lax` et jeton CSRF | `config/session.php`, `security.php` |
| Session abandonnée | Expiration après 30 minutes d'inactivité | `config/session.php` |
| Accès non autorisé | Vérification du rôle côté serveur | `Controllers/AccessController.php`, `admin/index.php` |
| Déconnexion déclenchée par un lien externe | Formulaire POST avec jeton CSRF | Navbar et sidebar |
| Clickjacking et MIME sniffing | En-têtes HTTP | `config/security.php` |

## 8. Niveau PHP utilisé

Le code du module utilisateurs privilégie les notions du cours : variables, tableaux, conditions, boucles `foreach`, fonctions, classes, objets, constructeur, attributs `private`, getters/setters, `require_once`, PDO et requêtes préparées.

Les doublons d'e-mail sont vérifiés en PHP avec PDO, et non par une requête AJAX en JavaScript. JavaScript sert uniquement à la validation simple dans le navigateur ; PHP reste obligatoire pour la sécurité.

## 9. Scénarios de test avant la soutenance

1. Créer un compte Citoyen puis se connecter.
2. Créer un compte Agent avec le code `SANTE-2026`.
3. Créer un compte Sécurité avec le code `ADMIN-2026`.
4. Tester un mauvais mot de passe.
5. Tester une adresse e-mail déjà enregistrée.
6. Connecté comme citoyen, ouvrir directement `admin/index.php?page=dashboard` : l'accès doit être refusé.
7. Avec un compte Sécurité, ajouter, modifier et supprimer un utilisateur (sans supprimer son propre compte).
8. Envoyer un formulaire sans jeton CSRF : le traitement doit être refusé.
9. Tester un nom vide, un e-mail invalide et deux mots de passe différents.
10. Vérifier dans phpMyAdmin que le mot de passe enregistré est un hachage et non le mot de passe en clair.
11. Connecté comme Citoyen, modifier le nom et l'e-mail depuis `Mon profil`, puis vérifier que les changements sont affichés.
12. Modifier le mot de passe depuis le profil et tester la connexion avec le nouveau mot de passe.
13. Essayer de supprimer le compte avec un mauvais mot de passe : la suppression doit être refusée.
14. Supprimer son compte avec le bon mot de passe : la session doit être fermée et l'utilisateur redirigé vers l'accueil.
15. Vérifier qu'un utilisateur ne peut pas changer son propre rôle depuis le formulaire de profil.

## 10. Limites connues de cette version

- Les statistiques de dons et les lignes « Dons récents » du tableau de bord sont des données de démonstration. Le compteur des utilisateurs et les statistiques par rôle proviennent de MySQL.
- Le code de sécurité fourni à l'inscription est une solution pédagogique pour la démonstration, pas un système de création de comptes administrateurs adapté à un site en production.
- Les fonctions de dons, demandes, campagnes et messages visibles dans le thème public ou le menu du backoffice ne constituent pas toutes des modules complets reliés à la base de données dans cette version. Le périmètre réellement intégré ici est la gestion des comptes et des accès. Les espaces Citoyen et Agent de santé affichent désormais les informations du compte et un accès à `Mon profil`, sans raccourcis de campagnes ou de dons.
