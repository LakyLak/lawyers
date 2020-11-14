<?php


namespace app\core;


class View
{
    public $title;

    public function renderView(string $view, array $params = []): string
    {
        $view = $this->viewContent($view, $params);
        $layout = $this->layoutContent();

        return str_replace('{{content}}', $view, $layout);
    }

    public function renderPartial(string $view, array $params = [])
    {
        $view = $this->viewContent($view, $params);

        echo $view;
    }

    protected function layoutContent()
    {
        $layout = Application::$app->controller->layout ?? 'main';

        ob_start();
        include_once Application::$root_directory."/views/layouts/$layout.php";

        return ob_get_clean();
    }

    protected function viewContent($view, $params)
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once Application::$root_directory."/views/$view.php";

        return ob_get_clean();
    }
}
