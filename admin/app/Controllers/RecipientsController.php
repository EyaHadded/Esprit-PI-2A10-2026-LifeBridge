<?php
require_once __DIR__ . '/../Models/Model.php';
class RecipientsController extends Controller {
    protected string $page  = 'receveurs';
    protected string $title = 'Receveurs | LifeBridge';
    public function index(): void {
        $this->render('table', [
            'heading' => 'Receveurs',
            'columns' => ['Nom','Email','Date','Statut'],
            'rows'    => Model::users(),
        ]);
    }
}
