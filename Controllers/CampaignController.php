<?php
require_once __DIR__ . '/Controller.php';
class CampaignController extends Controller
{
    public function index()
    {
        require_once __DIR__ . '/../Models/Campaign.php';
        $data = array('campagnes' => Campaign::all());
        $this->render('campagnes', $data);
    }
}
