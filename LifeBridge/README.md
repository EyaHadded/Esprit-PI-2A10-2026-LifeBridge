# LifeBridge

## Overview
LifeBridge is a PHP web application developed at **Esprit School of Engineering – Tunisia** (Academic Year 2026–2027), as part of the Web Technologies Project (2A). This module provides user management with secure authentication, sessions and role-based access (frontoffice / backoffice).

## Features
- User registration (password hashing with `password_hash()`)
- Login / logout with secure PHP sessions
- Roles: `user` (frontoffice) and `admin` (backoffice)
- Access control on every sensitive page (`requireAuth()`, `requireRole()`)
- Protection against SQL injection, XSS, CSRF and session fixation
- Server-side input validation (PHP) and client-side validation (JS) — *to complete*
- Full CRUD on users (backoffice) — *to complete*

## Tech Stack
### Frontend
- HTML5, CSS3, JavaScript
- Bootstrap / template integration (to complete)

### Backend
- PHP 8 (MVC architecture), PDO
- MySQL (phpMyAdmin / XAMPP)

## Architecture
```
LifeBridge/
├── app/
│   ├── controllers/AuthController.php
│   ├── models/UserModel.php
│   └── views/ (layout, layout_admin, login, register, user_space, admin_space)
├── config/database.php
├── public/ (css, js, images)
├── sql/users.sql
├── index.php
└── README.md
```

## Contributors
- Member 1 — *name*
- Member 2 — *name*

## Academic Context
Developed at **Esprit School of Engineering – Tunisia**
Web Technologies Project 2A | 2026–2027

## Getting Started
1. Install XAMPP and start **Apache** and **MySQL**.
2. Clone this repository into `C:\xampp\htdocs\`.
3. Open phpMyAdmin and import `sql/users.sql` (creates the `life_bridge` database and the `users` table).
4. Check the credentials in `config/database.php`.
5. Open `http://localhost/LifeBridge/index.php?action=register`.
6. To create an admin: `UPDATE users SET role = 'admin' WHERE email = 'your@email.com';`

## Acknowledgments
Thanks to the Esprit School of Engineering teaching team for the guidance and workshops.
