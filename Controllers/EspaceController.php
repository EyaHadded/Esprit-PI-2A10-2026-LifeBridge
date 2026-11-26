<?php
/** Espaces réservés au Citoyen et à l'Agent de santé. */
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/UserService.php';
require_once __DIR__ . '/AccessController.php';
require_once __DIR__ . '/Validator.php';

class EspaceController extends Controller
{
    public function citoyen()
    {
        AccessController::requireRole('citoyen');
        $this->render('espace-citoyen', array('user' => $this->currentUser()));
    }

    public function sante()
    {
        AccessController::requireRole('agent_sante');
        $this->render('espace-sante', array('user' => $this->currentUser()));
    }

    /** Profil personnel : modification ou suppression du compte connecté. */
    public function profil()
    {
        AccessController::requireAuth();

        $role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
        if ($role !== 'citoyen' && $role !== 'agent_sante') {
            http_response_code(403);
            die("Accès refusé : cette page est réservée aux citoyens et aux agents de santé.");
        }

        $users = new UserService();
        $user = $this->currentUser();
        $errors = array();
        $updated = isset($_GET['updated']) && $_GET['updated'] === '1';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : null;

            if (!Csrf::verify($token)) {
                $errors[] = 'Jeton de sécurité invalide. Veuillez réessayer.';
            } else {
                $action = isset($_POST['profile_action']) ? $_POST['profile_action'] : '';

                if ($action === 'update') {
                    $errors = Validator::user($_POST, false);
                    $nom = isset($_POST['nom']) && is_string($_POST['nom']) ? trim($_POST['nom']) : '';
                    $email = isset($_POST['email']) && is_string($_POST['email']) ? trim($_POST['email']) : '';
                    $password = isset($_POST['password']) && is_string($_POST['password']) ? $_POST['password'] : '';

                    if ($email !== '' && $users->emailExists($email, $user->getId())) {
                        $errors[] = 'Cette adresse e-mail est déjà utilisée par un autre compte.';
                    }

                    if (empty($errors)) {
                        $passwordHash = null;
                        if ($password !== '') {
                            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                        }

                        if ($users->updateProfile($user->getId(), $nom, $email, $passwordHash)) {
                            $_SESSION['nom'] = $nom;
                            AccessController::redirect('index.php?page=profil&updated=1');
                        }
                        $errors[] = 'La modification du profil a échoué. Veuillez réessayer.';
                    }
                } elseif ($action === 'delete') {
                    $currentPassword = isset($_POST['current_password']) && is_string($_POST['current_password'])
                        ? $_POST['current_password'] : '';

                    if ($currentPassword === '') {
                        $errors[] = 'Saisissez votre mot de passe pour confirmer la suppression.';
                    } elseif (!password_verify($currentPassword, $user->getPassword())) {
                        $errors[] = 'Le mot de passe saisi est incorrect.';
                    } elseif ($users->delete($user->getId())) {
                        destroySession();
                        AccessController::redirect('index.php?page=home&account_deleted=1');
                    } else {
                        $errors[] = 'La suppression du compte a échoué. Veuillez réessayer.';
                    }
                } else {
                    $errors[] = 'Action inconnue.';
                }
            }

            // Recharge le compte et conserve les valeurs saisies en cas d'erreur.
            $user = $users->findById((int) $_SESSION['user_id']);
            if ($user !== null && isset($_POST['profile_action']) && $_POST['profile_action'] === 'update') {
                if (isset($_POST['nom']) && is_string($_POST['nom'])) {
                    $user->setNom(trim($_POST['nom']));
                }
                if (isset($_POST['email']) && is_string($_POST['email'])) {
                    $user->setEmail(trim($_POST['email']));
                }
            }
        }

        $this->render('profil', array(
            'user' => $user,
            'errors' => $errors,
            'updated' => $updated,
            'csrfToken' => Csrf::token(),
            'roleLabel' => AccessController::$LABELS[$role]
        ));
    }

    private function currentUser()
    {
        $user = (new UserService())->findById((int) $_SESSION['user_id']);
        if ($user === null) {
            destroySession();
            AccessController::redirect('index.php?page=connexion');
        }
        return $user;
    }
}
