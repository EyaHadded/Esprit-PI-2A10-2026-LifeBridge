<?php
class SettingsController extends Controller {
    protected string $page  = 'parametres';
    protected string $title = 'Paramètres | LifeBridge';
    public function index(): void { $this->render('settings'); }
}
