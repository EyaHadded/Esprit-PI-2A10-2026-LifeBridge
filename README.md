# Life Bridge – Blood and Organ Donation Management Platform

## Overview

This project was developed as part of the **Web Technologies Project (2A)** at **Esprit School of Engineering – Tunisia** (Academic Year 2026–2027).

**Life Bridge** is a PHP web application for managing blood and organ donation. This module provides secure user management: registration, authentication, role-based access control (frontoffice for users, backoffice for administrators) and a complete CRUD on users.

## Features

- User registration with password hashing (`password_hash()`)
- Login / logout with secure PHP sessions (30-minute inactivity timeout)
- Role-based access control: `user` (frontoffice) and `admin` (backoffice)
- Backoffice dashboard with statistics
- Full user management (admin): list, search, add, edit, delete, change role
- Business rules: an administrator cannot delete or demote their own account
- Input validation on all forms: **PHP** (server side) and **JavaScript** (client side)
- Security: prepared statements (SQL injection), output escaping (XSS), CSRF tokens, session fixation protection, HTTP security headers

## Tech Stack

### Frontend

- HTML5, CSS3, JavaScript (client-side validation)
- Frontoffice and backoffice templates (integration in progress)

### Backend

- PHP 8 – MVC architecture
- PDO (MySQL)
- MySQL / phpMyAdmin (XAMPP)

## Architecture

The project follows the **MVC** pattern:

```
life-bridge/
├── app/
│   ├── controllers/
│   │   ├── AuthController.php    # authentication, sessions, roles, CSRF
│   │   └── UserController.php    # users CRUD (admin)
│   ├── models/
│   │   └── UserModel.php         # user entity (attributes, getters/setters)
│   └── views/                    # HTML only (layouts, login, register, dashboards, users CRUD)
├── config/
│   └── database.php              # PDO connection (Singleton)
├── public/
│   ├── css/
│   ├── js/
│   │   └── validation.js         # client-side validation
│   └── images/
├── sql/
│   └── users.sql                 # database schema
├── index.php                     # single entry point + routing (?action=...)
└── README.md
```

## Contributors

| Name | GitHub |
|------|--------|
| Fattouma Ben Rabiha | [@Fattouma87](https://github.com/Fattouma87) |
| Eya Hadded | [@EyaHadded](https://github.com/EyaHadded) |
| Hiba Krida | *to complete* |
| Mohamed Salah Allouche | *to complete* |

## Academic Context

Developed at **Esprit School of Engineering – Tunisia**
Web Technologies Project – 2A | 2026–2027

## Getting Started

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP 8)
- Git

### Installation

1. Clone the repository into the XAMPP web folder:

   ```bash
   cd C:\xampp\htdocs
   git clone <repository-url> LifeBridge
   ```

2. Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. Open phpMyAdmin (`http://localhost/phpmyadmin`) → **Import** → select `sql/users.sql`.
   This creates the `life_bridge` database and the `users` table.

### Configuration

Check the database credentials in `config/database.php` (default XAMPP: user `root`, empty password, database `life_bridge`).

### Run

Open `http://localhost/LifeBridge/index.php`

### Create an administrator

1. Register a normal account at `index.php?action=register`.
2. In phpMyAdmin (SQL tab), run:

   ```sql
   UPDATE users SET role = 'admin' WHERE email = 'your@email.com';
   ```

3. Log out and log in again: you are redirected to the backoffice.

## Acknowledgments

Thanks to the teaching team of **Esprit School of Engineering** for the workshops and guidance.
