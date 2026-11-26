<?php
require_once __DIR__ . '/../Models/Model.php';
class OrganDonationsController extends Controller {
    protected string $page  = 'dons-organes';
    protected string $title = "Dons d'organes | LifeBridge";
    public function index(): void {
        $this->render('table', [
            'heading' => "Dons d'organes",
            'columns' => ['Nom','Email','Date','Statut'],
            'rows'    => Model::users(),
        ]);
    }
}
