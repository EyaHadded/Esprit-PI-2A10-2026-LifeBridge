# MonProjet — Authentification à 3 rôles (MVC PHP)

## Installation
1. Copier le dossier dans `htdocs` (XAMPP) ou `www` (WAMP), ou lancer `php -S localhost:8000` dans le dossier.
2. Importer `database.sql` dans phpMyAdmin (crée la base `projet_web` et la table `users`).
3. Adapter si besoin l'utilisateur / mot de passe MySQL dans `config/database.php`.
4. Ouvrir `http://localhost/monprojet/index.php`.

## Rôles et pages
| Rôle (base)   | Page            | Action URL                  | Code d'inscription |
|---------------|-----------------|-----------------------------|--------------------|
| citoyen       | Page Citoyen    | index.php?action=citoyen    | aucun              |
| agent_sante   | Page Agent santé| index.php?action=sante      | SANTE-2026         |
| securite      | Page Administration | index.php?action=administration | ADMIN-2026 |

Les codes se changent dans `config/config.php`.

## Structure
- config/ : database.php (PDO Singleton), config.php (codes)
- models/UserModel.php : attributs + getters/setters
- controllers/AuthController.php : logique (SQL préparé, hachage, sessions, CSRF, rôles)
- views/ : login, register, 3 pages d'espace, layout + partials (head, header, footer)
- index.php : point d'entrée, session sécurisée, en-têtes, routage
