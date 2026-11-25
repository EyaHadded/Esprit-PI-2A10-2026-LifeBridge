<?php
/** Gestion de la session : cookies protégés et expiration après 30 minutes. */
const SESSION_LIFETIME = 1800;

function startSecureSession()
{
    // Ces réglages doivent être faits avant session_start().
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');

    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', '1');
    }

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    // Déconnecte l'utilisateur après 30 minutes sans activité.
    if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > SESSION_LIFETIME) {
        $_SESSION = array();
        session_regenerate_id(true);
        $_SESSION['expired'] = true;
    }

    $_SESSION['last_activity'] = time();
}

function loginSession($id, $nom, $role)
{
    session_regenerate_id(true);
    $_SESSION['user_id'] = $id;
    $_SESSION['nom'] = $nom;
    $_SESSION['role'] = $role;
    $_SESSION['last_activity'] = time();
}

function destroySession()
{
    $_SESSION = array();

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
}

function setFlash($message, $type = 'success')
{
    $_SESSION['flash'] = array('message' => $message, 'type' => $type);
}

function pullFlash()
{
    $flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : null;
    unset($_SESSION['flash']);
    return $flash;
}
