<?php
declare(strict_types=1);

// 1. Configuration sécurisée de la session
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
// ini_set('session.cookie_secure', '1'); // à décommenter en HTTPS
session_start();

// 2. Expiration après 30 minutes d'inactivité
$sessionLifetime = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $sessionLifetime)) {
    $_SESSION = [];
    session_destroy();
    session_start();
}
$_SESSION['last_activity'] = time();

// 3. En-têtes HTTP de sécurité
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline'");
header('Cache-Control: no-store'); // pas de page protégée en cache (bouton Précédent)

// 4. Contrôleur
require_once __DIR__ . '/controllers/AuthController.php';
$authController = new AuthController();

// 5. Routage
$action = $_GET['action'] ?? 'home';

switch ($action) {

    // ---------------- ACCUEIL ----------------
    case 'home':
        if (AuthController::isAuthenticated()) {
            header('Location: index.php?action=' . AuthController::homeAction($_SESSION['role'] ?? null));
        } else {
            header('Location: index.php?action=login');
        }
        exit;

    // ---------------- INSCRIPTION ----------------
    case 'register':
        if (AuthController::isAuthenticated()) {
            header('Location: index.php?action=home');
            exit;
        }
        $errors = [];
        $old = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $authController->register($_POST);
            $old = [
                'username' => $_POST['username'] ?? '',
                'email'    => $_POST['email'] ?? '',
                'role'     => $_POST['role'] ?? 'citoyen',
            ];
            if (empty($errors)) {
                header('Location: index.php?action=login&registered=1');
                exit;
            }
        }
        $csrfToken = $authController->getCsrfToken();
        require __DIR__ . '/views/register.php';
        break;

    // ---------------- CONNEXION ----------------
    case 'login':
        if (AuthController::isAuthenticated()) {
            header('Location: index.php?action=home');
            exit;
        }
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $authController->login($_POST);
            if (empty($errors)) {
                header('Location: index.php?action=' . AuthController::homeAction($_SESSION['role'] ?? null));
                exit;
            }
        }
        $csrfToken = $authController->getCsrfToken();
        require __DIR__ . '/views/login.php';
        break;

    // ---------------- DÉCONNEXION ----------------
    case 'logout':
        $authController->logout();
        header('Location: index.php?action=login');
        exit;

    // ---------------- ESPACE CITOYEN ----------------
    case 'citoyen':
        AuthController::requireRole('citoyen');
        $user = $authController->findById((int) $_SESSION['user_id']);
        if (!$user) {
            $authController->logout();
            header('Location: index.php?action=login');
            exit;
        }
        require __DIR__ . '/views/citoyen_space.php';
        break;

    // ---------------- ESPACE AGENT DE SANTÉ ----------------
    case 'sante':
        AuthController::requireRole('agent_sante');
        $user = $authController->findById((int) $_SESSION['user_id']);
        if (!$user) {
            $authController->logout();
            header('Location: index.php?action=login');
            exit;
        }
        require __DIR__ . '/views/sante_space.php';
        break;

    // ---------------- ESPACE ADMINISTRATION (sécurité) ----------------
    case 'administration':
        AuthController::requireRole('securite');
        $user = $authController->findById((int) $_SESSION['user_id']);
        if (!$user) {
            $authController->logout();
            header('Location: index.php?action=login');
            exit;
        }
        $allUsers      = $authController->getAllUsers();
        $totalCitoyens = $authController->countByRole('citoyen');
        $totalSante    = $authController->countByRole('agent_sante');
        $totalSecurite = $authController->countByRole('securite');
        require __DIR__ . '/views/administration_space.php';
        break;

    default:
        http_response_code(404);
        echo '404 - Page introuvable';
        break;
}
