<?php
declare(strict_types=1);

// 1. Configuration securisee de la session (AVANT session_start)
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
// ini_set('session.cookie_secure', '1'); // a activer si HTTPS
session_start();

// 2. Expiration par inactivite (30 min)
$sessionLifetime = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $sessionLifetime)) {
    $_SESSION = [];
    session_destroy();
    session_start();
}
$_SESSION['last_activity'] = time();

// 3. En-tetes HTTP de securite
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline'");

// 4. Controleurs
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/UserController.php';
$authController = new AuthController();
$userController = new UserController();

/**
 * Verifie que l'utilisateur est admin et retourne son modele.
 */
function loadAdmin(AuthController $auth): UserModel
{
    AuthController::requireRole('admin');
    $admin = $auth->findById((int) $_SESSION['user_id']);
    if (!$admin) {
        $auth->logout();
        header('Location: index.php?action=login');
        exit;
    }
    return $admin;
}

// 5. Routage
$action = $_GET['action'] ?? 'home';

switch ($action) {

    case 'home':
        if (AuthController::isAuthenticated()) {
            header('Location: index.php?action=' . (($_SESSION['role'] ?? '') === 'admin' ? 'admin' : 'dashboard'));
        } else {
            header('Location: index.php?action=login');
        }
        exit;

    case 'register':
        if (AuthController::isAuthenticated()) {
            header('Location: index.php?action=dashboard');
            exit;
        }
        $errors = [];
        $old = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $authController->register($_POST);
            $old = ['username' => $_POST['username'] ?? '', 'email' => $_POST['email'] ?? ''];
            if (empty($errors)) {
                header('Location: index.php?action=login&registered=1');
                exit;
            }
        }
        $csrfToken = $authController->getCsrfToken();
        require __DIR__ . '/app/views/register.php';
        break;

    case 'login':
        if (AuthController::isAuthenticated()) {
            header('Location: index.php?action=dashboard');
            exit;
        }
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $authController->login($_POST);
            if (empty($errors)) {
                $redirect = ($_SESSION['role'] ?? '') === 'admin' ? 'admin' : 'dashboard';
                header('Location: index.php?action=' . $redirect);
                exit;
            }
        }
        $csrfToken = $authController->getCsrfToken();
        require __DIR__ . '/app/views/login.php';
        break;

    case 'logout':
        $authController->logout();
        header('Location: index.php?action=login');
        exit;

    case 'dashboard':
        AuthController::requireAuth();
        $user = $authController->findById((int) $_SESSION['user_id']);
        if (!$user) {
            $authController->logout();
            header('Location: index.php?action=login');
            exit;
        }
        require __DIR__ . '/app/views/user_space.php';
        break;

    // ---------------- BACKOFFICE (admin uniquement) ----------------
    case 'admin':
        $user = loadAdmin($authController);
        $allUsers = $authController->getAllUsers();
        $totalUsers = $authController->countAllUsers();
        $totalAdmins = $authController->countByRole('admin');
        $totalRegular = $authController->countByRole('user');
        require __DIR__ . '/app/views/admin_space.php';
        break;

    // READ : liste + recherche
    case 'users':
        $user = loadAdmin($authController);
        $search = trim($_GET['q'] ?? '');
        $users = $userController->search($search);
        $flash = UserController::pullFlash();
        $csrfToken = $userController->getCsrfToken();
        require __DIR__ . '/app/views/users_list.php';
        break;

    // CREATE
    case 'user_add':
        $user = loadAdmin($authController);
        $mode = 'add';
        $target = null;
        $errors = [];
        $old = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $userController->create($_POST);
            $old = [
                'username' => $_POST['username'] ?? '',
                'email'    => $_POST['email'] ?? '',
                'role'     => $_POST['role'] ?? 'user',
            ];
            if (empty($errors)) {
                UserController::flash('success', 'User created successfully.');
                header('Location: index.php?action=users');
                exit;
            }
        }
        $csrfToken = $userController->getCsrfToken();
        require __DIR__ . '/app/views/user_form.php';
        break;

    // UPDATE
    case 'user_edit':
        $user = loadAdmin($authController);
        $id = (int) ($_GET['id'] ?? 0);
        $target = $userController->find($id);
        if (!$target) {
            UserController::flash('error', 'User not found.');
            header('Location: index.php?action=users');
            exit;
        }
        $mode = 'edit';
        $errors = [];
        $old = [
            'username' => $target->getUsername(),
            'email'    => $target->getEmail(),
            'role'     => $target->getRole(),
        ];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $userController->update($id, $_POST, (int) $_SESSION['user_id']);
            if (empty($errors)) {
                UserController::flash('success', 'User updated successfully.');
                header('Location: index.php?action=users');
                exit;
            }
            $old = [
                'username' => $_POST['username'] ?? '',
                'email'    => $_POST['email'] ?? '',
                'role'     => $_POST['role'] ?? $target->getRole(),
            ];
        }
        $csrfToken = $userController->getCsrfToken();
        require __DIR__ . '/app/views/user_form.php';
        break;

    // DELETE (POST uniquement)
    case 'user_delete':
        loadAdmin($authController);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $userController->delete((int) ($_GET['id'] ?? 0), $_POST, (int) $_SESSION['user_id']);
            if (empty($errors)) {
                UserController::flash('success', 'User deleted.');
            } else {
                UserController::flash('error', $errors[0]);
            }
        }
        header('Location: index.php?action=users');
        exit;

    // CHANGE ROLE (POST uniquement)
    case 'user_role':
        loadAdmin($authController);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $userController->changeRole((int) ($_GET['id'] ?? 0), $_POST, (int) $_SESSION['user_id']);
            if (empty($errors)) {
                UserController::flash('success', 'Role updated.');
            } else {
                UserController::flash('error', $errors[0]);
            }
        }
        header('Location: index.php?action=users');
        exit;

    default:
        http_response_code(404);
        echo '404 - Page not found';
        break;
}
