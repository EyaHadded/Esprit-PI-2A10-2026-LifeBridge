<?php
/** Espaces réservés au Citoyen et à l'Agent de santé. */
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/UserService.php';
require_once __DIR__ . '/AccessController.php';

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
