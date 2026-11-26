<?php
require_once __DIR__ . '/../Models/Model.php';
class CampaignsController extends Controller {
    protected string $page  = 'campagnes';
    protected string $title = 'Campagnes | LifeBridge';
    public function index(): void {
        $this->render('table', [
            'heading' => 'Campagnes',
            'columns' => ['Nom','Email','Date','Statut'],
            'rows'    => Model::users(),
        ]);
    }
}
