CREATE DATABASE IF NOT EXISTS life_bridge CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE life_bridge;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Pour creer un admin : inscris-toi via le formulaire, puis execute :
-- UPDATE users SET role = 'admin' WHERE email = 'ton@email.com';
