<?php
/** Vérification de l'identité et des droits des utilisateurs. */
class AccessController
{
    public static $LABELS = array(
        'citoyen' => 'Citoyen',
        'agent_sante' => 'Agent de santé',
        'securite' => 'Sécurité (administration)'
    );

    public static $HOME_URLS = array(
        'citoyen' => 'index.php?page=espace-citoyen',
        'agent_sante' => 'index.php?page=espace-sante',
        'securite' => 'admin/index.php?page=dashboard'
    );

    public static $loginUrl = 'index.php?page=connexion';

    public static function homeUrl($role)
    {
        if (isset(self::$HOME_URLS[$role])) {
            return self::$HOME_URLS[$role];
        }
        return 'index.php?page=connexion';
    }

    public static function isAuthenticated()
    {
        return !empty($_SESSION['user_id']);
    }

    public static function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    public static function requireAuth()
    {
        if (!self::isAuthenticated()) {
            self::redirect(self::$loginUrl);
        }
    }

    public static function requireRole($role)
    {
        self::requireAuth();

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
            http_response_code(403);
            die("Accès refusé : vous n'avez pas les permissions nécessaires.");
        }
    }
}
