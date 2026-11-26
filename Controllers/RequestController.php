<?php
require_once __DIR__ . '/Controller.php';
class RequestController extends Controller
{
    public function index()
    {
        $this->render('demandes', array());
    }
}
