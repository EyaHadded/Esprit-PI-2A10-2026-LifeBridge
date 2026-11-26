<?php
/** Contrôleur de base du backoffice : prépare la vue puis le layout. */
class Controller
{
    protected $page = 'dashboard';
    protected $title = 'LifeBridge Admin';

    protected function render($view, $data = array(), $scriptsView = null)
    {
        $content = $this->capture(__DIR__ . '/../Views/pages/' . $view . '.php', $data);
        $extraScripts = '';
        if ($scriptsView !== null) {
            $extraScripts = $this->capture(__DIR__ . '/../Views/pages/' . $scriptsView . '.php', $data);
        }
        $title = $this->title;
        $currentPage = $this->page;
        require __DIR__ . '/../Views/layout.php';
    }

    private function capture($file, $data)
    {
        extract($data);
        ob_start();
        require $file;
        return ob_get_clean();
    }
}
