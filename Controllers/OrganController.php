<?php
require_once __DIR__ . '/Controller.php';
class OrganController extends Controller
{
    public function index()
    {
        $this->render('don-organes', array());
    }
}
