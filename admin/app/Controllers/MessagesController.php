<?php
require_once __DIR__ . '/../Models/Model.php';
class MessagesController extends Controller {
    protected string $page  = 'messages';
    protected string $title = 'Messages | LifeBridge';
    public function index(): void {
        $this->render('table', [
            'heading' => 'Messages',
            'columns' => ['Nom','Email','Date','Statut'],
            'rows'    => Model::users(),
        ]);
    }
}
