<?php
// Point d'entrée du backoffice : seuls les utilisateurs du rôle securite y accèdent.
$root = dirname(__DIR__);
require_once $root . '/config/session.php';
require_once $root . '/config/security.php';
require_once $root . '/Controllers/AccessController.php';

startSecureSession();
sendSecurityHeaders();

AccessController::$loginUrl = '../index.php?page=connexion';
AccessController::requireRole('securite');

require_once __DIR__ . '/app/helpers.php';
$routes = require __DIR__ . '/app/routes.php';
$page = isset($_GET['page']) && is_string($_GET['page']) ? $_GET['page'] : 'dashboard';

if (!isset($routes[$page])) {
    http_response_code(404);
    exit('Page introuvable');
}

require_once __DIR__ . '/app/Controllers/Controller.php';
$controllerName = $routes[$page];
require_once __DIR__ . '/app/Controllers/' . $controllerName . '.php';

$action = isset($_GET['do']) && is_string($_GET['do']) ? $_GET['do'] : 'index';
$allowedActions = array('index', 'create', 'edit', 'delete');
$controller = new $controllerName();

if (!in_array($action, $allowedActions) || !method_exists($controller, $action)) {
    http_response_code(404);
    exit('Page introuvable');
}

$controller->$action();
