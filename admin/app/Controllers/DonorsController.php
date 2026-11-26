<?php
require_once __DIR__ . '/../Models/Model.php';
class DonorsController extends Controller {
    protected string $page  = 'donneurs';
    protected string $title = 'Donneurs | LifeBridge';
    public function index(): void {
        $this->render('table', [
            'heading' => 'Donneurs',
            'columns' => ['Nom','Email','Date','Statut'],
            'rows'    => Model::users(),
        ]);
    }
}
