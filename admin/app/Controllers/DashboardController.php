<?php
/** Tableau de bord du backoffice. Les compteurs utilisateurs viennent de MySQL. */
require_once __DIR__ . '/../Models/Model.php';
require_once __DIR__ . '/../../../Controllers/UserService.php';
require_once __DIR__ . '/../../../Controllers/AccessController.php';

class DashboardController extends Controller
{
    protected $page = 'dashboard';
    protected $title = 'Tableau de bord | LifeBridge';

    public function index()
    {
        $service = new UserService();
        $roles = array();

        foreach (AccessController::$LABELS as $role => $label) {
            $roles[$label] = $service->countByRole($role);
        }

        $latestUsers = $service->getLatest(5);
        $latest = array();
        foreach ($latestUsers as $user) {
            $date = strtotime($user->getCreatedAt());
            $latest[] = array(
                $user->getNom(),
                $user->getEmail(),
                $date ? date('d/m/Y', $date) : '',
                isset(AccessController::$LABELS[$user->getRole()]) ? AccessController::$LABELS[$user->getRole()] : $user->getRole()
            );
        }

        $this->render('dashboard', array(
            'stats' => array('sang' => 12580, 'organes' => 1240, 'users' => $service->countAll(), 'campagnes' => 8),
            'roles' => $roles,
            'users' => $latest,
            'dons' => Model::dons()
        ), 'dashboard_scripts');
    }
}
