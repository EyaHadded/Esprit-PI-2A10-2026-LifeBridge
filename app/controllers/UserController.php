<?php
/**
 * app/controllers/UserController.php
 * CRUD des utilisateurs (backoffice admin) : requetes preparees, validation, CSRF.
 */
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';

class UserController
{
    private const ROLES = ['user', 'admin'];

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnexion();
    }

    // ==============================================================
    // CSRF (meme cle de session que AuthController)
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

    // ==============================================================
    // FLASH MESSAGES (affiches une seule fois apres une redirection)
    // ==============================================================
    public static function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    public static function pullFlash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    // ==============================================================
    // READ
    // ==============================================================
    /** @return UserModel[] */
    public function search(string $q = ''): array
    {
        if ($q === '') {
            $stmt = $this->db->query('SELECT * FROM users ORDER BY id ASC');
        } else {
            $stmt = $this->db->prepare(
                'SELECT * FROM users WHERE username LIKE :q1 OR email LIKE :q2 ORDER BY id ASC'
            );
            $like = '%' . addcslashes($q, '%_\\') . '%';
            $stmt->execute(['q1' => $like, 'q2' => $like]);
        }
        return array_map(fn(array $row) => UserModel::fromArray($row), $stmt->fetchAll());
    }

    public function find(int $id): ?UserModel
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? UserModel::fromArray($row) : null;
    }

    private function existsOther(string $email, string $username, int $excludeId): bool
    {
        $stmt = $this->db->prepare(
            'SELECT id FROM users WHERE (email = :email OR username = :username) AND id <> :id LIMIT 1'
        );
        $stmt->execute(['email' => $email, 'username' => $username, 'id' => $excludeId]);
        return (bool) $stmt->fetch();
    }

    // ==============================================================
    // VALIDATION (cote serveur)
    // ==============================================================
    private function validateInput(array $post, int $editId): array
    {
        $errors = [];
        $username = trim($post['username'] ?? '');
        $email = trim($post['email'] ?? '');
        $password = $post['password'] ?? '';
        $confirm = $post['password_confirm'] ?? '';
        $role = $post['role'] ?? 'user';
        $isEdit = $editId > 0;

        if ($username === '' || $email === '') {
            $errors[] = "Username and email are required.";
        } else {
            if (!preg_match('/^[a-zA-Z0-9_\-]{3,50}$/', $username)) {
                $errors[] = "Username must be 3 to 50 characters (letters, digits, _ or -).";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
                $errors[] = "Invalid email address.";
            }
        }

        // Mot de passe : obligatoire a la creation, optionnel a la modification
        if (!$isEdit || $password !== '' || $confirm !== '') {
            if (strlen($password) < 8) {
                $errors[] = "Password must be at least 8 characters long.";
            }
            if ($password !== $confirm) {
                $errors[] = "Passwords do not match.";
            }
        }

        if (!in_array($role, self::ROLES, true)) {
            $errors[] = "Invalid role.";
        }

        if (empty($errors) && $this->existsOther($email, $username, $editId)) {
            $errors[] = "This email or username is already taken.";
        }

        return [
            'errors' => $errors,
            'data' => ['username' => $username, 'email' => $email, 'password' => $password, 'role' => $role],
        ];
    }

    // ==============================================================
    // CREATE
    // ==============================================================
    public function create(array $post): array
    {
        if (!$this->verifyCsrfToken($post['csrf_token'] ?? null)) {
            return ["Invalid security token. Please try again."];
        }

        $result = $this->validateInput($post, 0);
        if (!empty($result['errors'])) {
            return $result['errors'];
        }
        $d = $result['data'];

        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)'
        );
        $ok = $stmt->execute([
            'username' => $d['username'],
            'email'    => $d['email'],
            'password' => password_hash($d['password'], PASSWORD_DEFAULT),
            'role'     => $d['role'],
        ]);

        return $ok ? [] : ["An error occurred while creating the user."];
    }

    // ==============================================================
    // UPDATE
    // ==============================================================
    public function update(int $id, array $post, int $currentId): array
    {
        if (!$this->verifyCsrfToken($post['csrf_token'] ?? null)) {
            return ["Invalid security token. Please try again."];
        }

        $target = $this->find($id);
        if (!$target) {
            return ["User not found."];
        }

        $result = $this->validateInput($post, $id);
        if (!empty($result['errors'])) {
            return $result['errors'];
        }
        $d = $result['data'];

        // Un admin ne peut pas changer son propre role (evite de se retirer l'acces)
        if ($id === $currentId) {
            $d['role'] = $target->getRole();
        }

        $sql = 'UPDATE users SET username = :username, email = :email, role = :role';
        $params = [
            'username' => $d['username'],
            'email'    => $d['email'],
            'role'     => $d['role'],
            'id'       => $id,
        ];
        if ($d['password'] !== '') {
            $sql .= ', password = :password';
            $params['password'] = password_hash($d['password'], PASSWORD_DEFAULT);
        }
        $sql .= ' WHERE id = :id';

        $stmt = $this->db->prepare($sql);
        if (!$stmt->execute($params)) {
            return ["An error occurred while updating the user."];
        }

        if ($id === $currentId) {
            $_SESSION['username'] = $d['username'];
        }
        return [];
    }

    // ==============================================================
    // DELETE
    // ==============================================================
    public function delete(int $id, array $post, int $currentId): array
    {
        if (!$this->verifyCsrfToken($post['csrf_token'] ?? null)) {
            return ["Invalid security token. Please try again."];
        }
        if ($id === $currentId) {
            return ["You cannot delete your own account."];
        }
        if (!$this->find($id)) {
            return ["User not found."];
        }

        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
        return $stmt->execute(['id' => $id]) ? [] : ["An error occurred while deleting the user."];
    }

    // ==============================================================
    // CHANGE ROLE
    // ==============================================================
    public function changeRole(int $id, array $post, int $currentId): array
    {
        if (!$this->verifyCsrfToken($post['csrf_token'] ?? null)) {
            return ["Invalid security token. Please try again."];
        }
        $role = $post['role'] ?? '';
        if (!in_array($role, self::ROLES, true)) {
            return ["Invalid role."];
        }
        if ($id === $currentId) {
            return ["You cannot change your own role."];
        }
        if (!$this->find($id)) {
            return ["User not found."];
        }

        $stmt = $this->db->prepare('UPDATE users SET role = :role WHERE id = :id');
        return $stmt->execute(['role' => $role, 'id' => $id]) ? [] : ["An error occurred while changing the role."];
    }
}
