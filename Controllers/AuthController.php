<?php
/** Inscription, connexion et déconnexion des utilisateurs. */
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/UserService.php';
require_once __DIR__ . '/Validator.php';
require_once __DIR__ . '/AccessController.php';

class AuthController extends Controller
{
    private $users;
    private $config;

    public function __construct()
    {
        $this->users = new UserService();
        $this->config = require __DIR__ . '/../config/config.php';
    }

    public function index()
    {
        if (AccessController::isAuthenticated()) {
            AccessController::redirect(AccessController::homeUrl(isset($_SESSION['role']) ? $_SESSION['role'] : null));
        }

        $errors = array();
        $old = array('role' => 'citoyen');
        $tab = 'login';
        $expired = !empty($_SESSION['expired']);
        unset($_SESSION['expired']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = isset($_POST['form_type']) ? $_POST['form_type'] : '';

            if ($type === 'register') {
                $tab = 'register';
                $errors = $this->register($_POST);
                $old = array(
                    'nom' => isset($_POST['nom']) ? $_POST['nom'] : '',
                    'email' => isset($_POST['email']) ? $_POST['email'] : '',
                    'role' => isset($_POST['role']) ? $_POST['role'] : 'citoyen'
                );
                if (empty($errors)) {
                    AccessController::redirect('index.php?page=connexion&registered=1');
                }
            } elseif ($type === 'login') {
                $errors = $this->login($_POST);
                if (empty($errors)) {
                    AccessController::redirect(AccessController::homeUrl(isset($_SESSION['role']) ? $_SESSION['role'] : null));
                }
            }
        }

        $this->render('connexion', array(
            'errors' => $errors,
            'old' => $old,
            'tab' => $tab,
            'expired' => $expired,
            'csrfToken' => Csrf::token()
        ));
    }

    // La déconnexion est une action POST protégée par un jeton CSRF.
    public function logout()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Méthode non autorisée.');
        }
        if (!Csrf::verify(isset($_POST['csrf_token']) ? $_POST['csrf_token'] : null)) {
            http_response_code(403);
            exit('Jeton de sécurité invalide.');
        }

        destroySession();
        AccessController::redirect('index.php?page=connexion');
    }

    private function register($post)
    {
        if (!Csrf::verify(isset($post['csrf_token']) ? $post['csrf_token'] : null)) {
            return array('Jeton de sécurité invalide. Veuillez réessayer.');
        }

        $errors = Validator::user($post, true);
        $role = isset($post['role']) && is_string($post['role']) ? $post['role'] : 'citoyen';
        $code = isset($post['code']) && is_string($post['code']) ? trim($post['code']) : '';

        if (!is_string($role) || !isset(AccessController::$LABELS[$role])) {
            $errors[] = 'Type de compte invalide.';
        } elseif ($role !== 'citoyen') {
            $expected = isset($this->config['registration_codes'][$role]) ? $this->config['registration_codes'][$role] : '';
            if ($expected === '' || !hash_equals($expected, $code)) {
                $errors[] = "Code d'inscription incorrect pour ce type de compte.";
            }
        }

        if (!empty($errors)) {
            return $errors;
        }

        $nom = trim($post['nom']);
        $email = trim($post['email']);
        if ($this->users->emailExists($email)) {
            return array('Cette adresse e-mail est déjà utilisée.');
        }

        $user = new User($nom, $email, password_hash($post['password'], PASSWORD_DEFAULT), $role);
        if ($this->users->insert($user)) {
            return array();
        }
        return array('Une erreur est survenue lors de la création du compte.');
    }

    private function login($post)
    {
        if (!Csrf::verify(isset($post['csrf_token']) ? $post['csrf_token'] : null)) {
            return array('Jeton de sécurité invalide. Veuillez réessayer.');
        }

        $email = isset($post['email']) && is_string($post['email']) ? trim($post['email']) : '';
        $password = isset($post['password']) && is_string($post['password']) ? $post['password'] : '';
        if ($email === '' || $password === '') {
            return array('Veuillez saisir votre e-mail et votre mot de passe.');
        }

        $user = $this->users->findByEmail($email);
        if ($user === null || !password_verify($password, $user->getPassword())) {
            return array('E-mail ou mot de passe incorrect.');
        }

        loginSession($user->getId(), $user->getNom(), $user->getRole());
        return array();
    }
}
