<?php
class Controller
{
    protected function render($view, $data = array())
    {
        extract($data);
        require __DIR__ . '/../Views/' . $view . '.php';
    }
}

class Controller404 extends Controller
{
    public function index()
    {
        $this->render('404');
    }
}
