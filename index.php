<?php
// Point d'entrée du site public : session, sécurité et choix de la page.
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/security.php';
require_once __DIR__ . '/Controllers/AccessController.php';

startSecureSession();
sendSecurityHeaders();

$routes = array(
    'home' => 'HomeController',
    'don-sang' => 'BloodController',
    'don-organes' => 'OrganController',
    'campagnes' => 'CampaignController',
    'demandes' => 'RequestController',
    'contact' => 'ContactController',
    'connexion' => 'AuthController',
    'deconnexion' => 'AuthController@logout',
    'espace-citoyen' => 'EspaceController@citoyen',
    'espace-sante' => 'EspaceController@sante'
);

$page = isset($_GET['page']) && is_string($_GET['page']) ? $_GET['page'] : 'home';
require_once __DIR__ . '/Controllers/Controller.php';

if (!isset($routes[$page])) {
    http_response_code(404);
    (new Controller404())->index();
    exit;
}

$routeParts = explode('@', $routes[$page]);
$class = $routeParts[0];
$method = isset($routeParts[1]) ? $routeParts[1] : 'index';
require_once __DIR__ . '/Controllers/' . $class . '.php';
$controller = new $class();
$controller->$method();
