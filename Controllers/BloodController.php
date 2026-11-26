<?php
require_once __DIR__ . '/Controller.php';
class BloodController extends Controller
{
    public function index()
    {
        $data = array('groupes' => array('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'));
        $this->render('don-sang', $data);
    }
}
