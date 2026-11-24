<?php
/**
 * controllers/AuthController.php
 * Toute la logique métier : SQL préparé, hachage, sessions, CSRF, rôles.
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';

class AuthController
{
    /** Rôles autorisés => action (page) de destination */
    public const ROLES = [
        'citoyen'     => 'citoyen',
        'agent_sante' => 'sante',
        'securite'    => 'administration',
    ];

    /** Libellés affichés */
    public const LABELS = [
        'citoyen'     => 'Citoyen',
        'agent_sante' => 'Agent de santé',
        'securite'    => 'Sécurité (administration)',
    ];

    private PDO $db;
    private array $config;

    public function __construct()
    {
        $this->db = Database::getConnexion();
        $this->config = require __DIR__ . '/../config/config.php';
    }

    // ==============================================================
    // ÉTAT DE SESSION
    // ==============================================================
    public static function isAuthenticated(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    public static function homeAction(?string $role): string
    {
        return self::ROLES[$role ?? ''] ?? 'login';
    }

    public static function requireAuth(): void
    {
        if (!self::isAuthenticated()) {
            header('Location: index.php?action=login');
            exit;
        }
    }

    public static function requireRole(string $role): void
    {
        self::requireAuth();
        if (($_SESSION['role'] ?? null) !== $role) {
            http_response_code(403);
            die("Accès refusé : vous n'avez pas les permissions nécessaires.");
        }
    }

    // ==============================================================
    // ACCÈS AUX DONNÉES (requêtes préparées)
    // ==============================================================
    private function existsByEmailOrUsername(string $email, string $username): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM users WHERE email = :email OR username = :username LIMIT 1');
        $stmt->execute(['email' => $email, 'username' => $username]);
        return (bool) $stmt->fetch();
    }

    private function insertUser(UserModel $user): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)'
        );
        return $stmt->execute([
            'username' => $user->getUsername(),
            'email'    => $user->getEmail(),
            'password' => $user->getPassword(),
            'role'     => $user->getRole(),
        ]);
    }

    private function findByEmail(string $email): ?UserModel
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        return $row ? UserModel::fromArray($row) : null;
    }

    public function findById(int $id): ?UserModel
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? UserModel::fromArray($row) : null;
    }

    /** @return UserModel[] */
    public function getAllUsers(): array
    {
        $rows = $this->db->query('SELECT * FROM users ORDER BY id ASC')->fetchAll();
        return array_map(fn(array $r) => UserModel::fromArray($r), $rows);
    }

    public function countByRole(string $role): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE role = :role');
        $stmt->execute(['role' => $role]);
        return (int) $stmt->fetchColumn();
    }

    // ==============================================================
    // INSCRIPTION
    // ==============================================================
    public function register(array $post): array
    {
        $errors = [];

        if (!$this->verifyCsrfToken($post['csrf_token'] ?? null)) {
            $errors[] = "Jeton de sécurité invalide. Veuillez réessayer.";
            return $errors;
        }

        $username        = trim($post['username'] ?? '');
        $email           = trim($post['email'] ?? '');
        $password        = $post['password'] ?? '';
        $passwordConfirm = $post['password_confirm'] ?? '';
        $role            = $post['role'] ?? 'citoyen';
        $code            = trim($post['code'] ?? '');

        if ($username === '' || $email === '' || $password === '') {
            $errors[] = "Tous les champs sont obligatoires.";
        }
        if (!preg_match('/^[a-zA-Z0-9_\-]{3,50}$/', $username)) {
            $errors[] = "Le nom d'utilisateur doit contenir 3 à 50 caractères (lettres, chiffres, _ ou -).";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Adresse email invalide.";
        }
        if (strlen($password) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
        }
        if ($password !== $passwordConfirm) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }
        if (!array_key_exists($role, self::ROLES)) {
            $errors[] = "Type de compte invalide.";
        } elseif ($role !== 'citoyen') {
            // Rôles à privilèges : code d'inscription vérifié côté serveur
            $expected = $this->config['registration_codes'][$role] ?? null;
            if ($expected === null || !hash_equals($expected, $code)) {
                $errors[] = "Code d'inscription incorrect pour ce type de compte.";
            }
        }

        if (!empty($errors)) {
            return $errors;
        }

        if ($this->existsByEmailOrUsername($email, $username)) {
            $errors[] = "Cet email ou ce nom d'utilisateur est déjà utilisé.";
            return $errors;
        }

        $user = new UserModel();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user->setRole($role);

        if (!$this->insertUser($user)) {
            $errors[] = "Une erreur est survenue lors de la création du compte.";
        }
        return $errors; // tableau vide = succès
    }

    // ==============================================================
    // CONNEXION / DÉCONNEXION
    // ==============================================================
    public function login(array $post): array
    {
        $errors = [];

        if (!$this->verifyCsrfToken($post['csrf_token'] ?? null)) {
            $errors[] = "Jeton de sécurité invalide. Veuillez réessayer.";
            return $errors;
        }

        $email    = trim($post['email'] ?? '');
        $password = $post['password'] ?? '';

        if ($email === '' || $password === '') {
            $errors[] = "Veuillez saisir votre email et votre mot de passe.";
            return $errors;
        }

        $user = $this->findByEmail($email);

        // Message volontairement générique
        if (!$user || !password_verify($password, $user->getPassword())) {
            $errors[] = "Email ou mot de passe incorrect.";
            return $errors;
        }

        session_regenerate_id(true); // anti fixation de session
        $_SESSION['user_id']       = $user->getId();
        $_SESSION['username']      = $user->getUsername();
        $_SESSION['role']          = $user->getRole();
        $_SESSION['last_activity'] = time();

        return [];
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }

    // ==============================================================
    // CSRF
    // ==============================================================
    public function getCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    private function verifyCsrfToken(?string $token): bool
    {
        if (!$token || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }
}
