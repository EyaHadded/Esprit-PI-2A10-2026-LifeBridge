# LifeBridge — Plateforme de sensibilisation au don de sang et d'organes

LifeBridge est un projet académique réalisé dans le cadre du projet intégré (PI). Il propose une interface web consacrée à la sensibilisation au don de sang et au don d'organes, avec un système d'inscription, de connexion et de gestion des utilisateurs selon leurs rôles.

**Développé à Esprit School of Engineering – Tunisia**  
**Année universitaire : 2026–2027**

## Table des matières

- [Overview](#overview)
- [Fonctionnalités](#fonctionnalités)
- [Tech Stack](#tech-stack)
- [Architecture](#architecture)
- [Contributors](#contributors)
- [Academic Context](#academic-context)
- [Getting Started](#getting-started)
- [Utilisation](#utilisation)
- [Sécurité](#sécurité)
- [Contribution](#contribution)
- [Acknowledgments](#acknowledgments)
- [Licence](#licence)

## Overview

Le don de sang et le don d'organes sont des actes essentiels qui peuvent sauver des vies. LifeBridge vise à présenter des informations sur ces formes de don et à proposer un espace web organisé pour les citoyens et les agents de santé.

Le projet comprend des pages publiques ainsi qu'un espace d'authentification et un backoffice destiné à la gestion des utilisateurs.

## Fonctionnalités

### Pages publiques
- Accueil
- Don de sang
- Don d'organes
- Campagnes
- Demandes
- Contact

### Comptes et espaces utilisateurs
- Inscription et connexion
- Trois rôles : Citoyen, Agent de santé et Sécurité (administration)
- Redirection vers l'espace correspondant au rôle
- Espace Citoyen et espace Agent de santé
- Consultation et modification du profil personnel
- Modification du mot de passe
- Suppression du compte personnel après confirmation du mot de passe

### Backoffice
- Tableau de bord d'administration
- Liste et gestion des utilisateurs
- Ajout, consultation, modification et suppression des utilisateurs

> **État du projet :** certaines pages liées aux dons, aux demandes, aux campagnes et aux messages constituent des pages de présentation ou des interfaces de démonstration. La base de données fournie contient actuellement la table `users`. Les fonctionnalités qui nécessitent une gestion complète des dons ou des demandes ne doivent pas être considérées comme un service de production.

## Tech Stack

### Frontend
- HTML5
- CSS3
- JavaScript

### Backend
- PHP
- MySQL
- PDO

## Architecture

Le projet utilise une organisation inspirée du modèle MVC :

- **Model** : représente les données et leurs opérations. Exemple : `Models/User.php`.
- **View** : contient les pages et éléments d'interface. Exemple : `Views/connexion.php`.
- **Controller** : traite les requêtes et sélectionne les vues. Exemple : `Controllers/AuthController.php`.
- **Service** : regroupe les opérations de base de données des utilisateurs. Exemple : `Controllers/UserService.php`.

### Structure principale

```text
LifeBridge/
├── admin/
│   ├── app/
│   │   ├── Controllers/
│   │   ├── Models/
│   │   └── Views/
│   └── index.php
├── config/
│   ├── config.php
│   ├── database.php
│   ├── security.php
│   └── session.php
├── Controllers/
├── Models/
├── Views/
│   ├── assets/
│   └── includes/
├── database.sql
└── index.php
```

## Contributors

Projet réalisé par une équipe de six étudiants. Chaque membre possède une responsabilité principale et participe à l'intégration et aux tests du projet.

| Membre | Responsabilité principale |
|---|---|
| Hadded Eya | Authentification : inscription, connexion et déconnexion |
| Krida Hiba | Sessions : gestion et sécurisation des sessions utilisateur |
| Lajnef Fatma | Gestion des utilisateurs : modèle et opérations CRUD |
| Ben Rabiha Fattouma | Rôles, espaces utilisateur et profil personnel |
| Belhaj Sghaier Youssef | Validation des formulaires côté client et côté serveur |
| Allouche Mohamed Salah | Base de données, configuration, sécurité et documentation |

## Academic Context

Ce projet a été développé dans le cadre du **PI** à **Esprit School of Engineering – Tunisia**, pendant l'année universitaire **2026–2027**.

Il met en pratique les notions de développement web, PHP, programmation orientée objet, formulaires, sessions, architecture MVC et accès à une base de données MySQL.

## Getting Started

### Prérequis

- PHP (avec l'extension PDO MySQL activée)
- MySQL ou MariaDB
- Apache
- Un navigateur web
- XAMPP recommandé pour une installation locale simple

### Installation avec XAMPP

1. Installer et lancer XAMPP.
2. Copier le dossier `LifeBridge` dans le dossier `htdocs` de XAMPP.
3. Démarrer **Apache** et **MySQL** depuis le panneau XAMPP.
4. Ouvrir phpMyAdmin : `http://localhost/phpmyadmin`.
5. Importer le fichier `database.sql` du projet. Il crée la base `lifebridge` et la table `users`.
6. Vérifier la configuration de la connexion dans `config/database.php`. La configuration locale par défaut utilise `root` avec un mot de passe vide.
7. Ouvrir le site :

```text
http://localhost/LifeBridge/
```

Le backoffice est accessible à un compte ayant le rôle `securite` :

```text
http://localhost/LifeBridge/admin/index.php?page=dashboard
```

### Installation avec Git

```bash
git clone https://github.com/<organisation-ou-utilisateur>/<nom-du-repository>.git
cd <nom-du-repository>
```

Puis copier le dossier du projet dans le répertoire web local de votre environnement PHP, importer `database.sql` dans MySQL et démarrer Apache et MySQL.

### Configuration des rôles

| Rôle | Accès | Code de démonstration à l'inscription |
|---|---|---|
| `citoyen` | Espace Citoyen | Aucun |
| `agent_sante` | Espace Agent de santé | `SANTE-2026` |
| `securite` | Backoffice | `ADMIN-2026` |

Les codes sont définis dans `config/config.php`. **Changez-les avant de partager ou déployer le projet.** Ils sont uniquement destinés à une démonstration académique.

## Utilisation

1. Ouvrir la page d'accueil.
2. Créer un compte Citoyen, ou sélectionner un rôle nécessitant un code de démonstration.
3. Se connecter avec l'adresse e-mail et le mot de passe.
4. Accéder à l'espace correspondant au rôle.
5. Depuis `Mon profil`, modifier les informations personnelles ou supprimer le compte en confirmant le mot de passe.
6. Pour administrer les utilisateurs, se connecter avec un compte Sécurité et ouvrir le backoffice.

## Sécurité

Le projet met en œuvre plusieurs mécanismes de sécurité :

- Hachage des mots de passe avec `password_hash()` et vérification avec `password_verify()`
- Requêtes préparées PDO pour les opérations SQL
- Validation des données côté serveur
- Échappement des sorties HTML avec `htmlspecialchars()`
- Jetons CSRF pour les formulaires sensibles
- Sessions sécurisées et renouvellement de l'identifiant de session
- Vérification des rôles et des permissions côté serveur

**Important :** la configuration fournie est destinée au développement local et à la présentation académique. Avant un déploiement public, configurez des identifiants de base de données dédiés, changez les codes de rôle, activez HTTPS et effectuez une revue de sécurité.

## Contribution

Pour contribuer au projet :

1. Récupérer le repository ou créer un fork.
2. Créer une branche dédiée à la fonctionnalité ou à la correction.
3. Effectuer les modifications.
4. Tester les pages et les formulaires concernés.
5. Ajouter et valider les fichiers avec un message de commit explicite.
6. Envoyer la branche sur GitHub et créer une Pull Request si nécessaire.

Exemple :

```bash
git checkout -b feature/nom-fonctionnalite
git add .
git commit -m "feat: description de la fonctionnalite"
git push -u origin feature/nom-fonctionnalite
```

## Acknowledgments

Nous remercions **Esprit School of Engineering – Tunisia**, nos enseignants et notre encadrement pour leur accompagnement dans la réalisation de ce projet académique.

## Licence

Ce projet est réalisé à des fins pédagogiques dans le cadre du PI. Aucune licence open source spécifique n'est actuellement définie. Si le projet doit être redistribué, ajoutez un fichier `LICENSE` et précisez la licence choisie.
