<?php
require_once __DIR__ . '/../Models/Model.php';
class BloodDonationsController extends Controller {
    protected string $page  = 'dons-sang';
    protected string $title = 'Dons de sang | LifeBridge';
    public function index(): void {
        $this->render('table', [
            'heading' => 'Dons de sang',
            'columns' => ['Nom','Email','Date','Statut'],
            'rows'    => Model::users(),
        ]);
    }
}
