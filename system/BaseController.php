<?php
namespace system;

class BaseController {

    public $title = 'Untitled';
    public $layout = 'app/views/layouts/main.php';
    public $errorLayout = 'app/views/layouts/error.php';
    public $assets;

    public function __construct()
    {
        $this->assets = App::$assets;
    }

    public static function className()
    {
        return get_called_class();
    }

    public function rules()
    {
        return null;
    }

    public function render($file, $datas = [])
    {
        $__render__ = 'app/views/' . $file . '.php';
        ob_start();
        require $__render__;
        ob_end_clean();
        extract($datas);
        return require $this->layout;
    }

    public function redirect($url)
    {
        return App::$url->redirect($url);
    }

    public function registerCss($css)
    {
        if (!in_array($css, $this->assets->css))
            return array_push($this->assets->css, $css);
    }

    public function registerJs($js)
    {
        if (!in_array($js, $this->assets->js))
            return array_push($this->assets->js, $js);
    }

    public function registerCssFile($css)
    {
        if (!in_array($css, $this->assets->cssFile))
            return array_push($this->assets->cssFile, $css);
    }

    public function registerJsFile($js)
    {
        if (!in_array($js, $this->assets->jsFile))
            return array_push($this->assets->jsFile, $js);
    }

    public function notFound($message = '')
    {
        if ($message == '')
            $message = 'The requested page does not exist.';

        $this->title = '404 Not Found';
        $__render__ = $this->errorLayout;
        require $this->layout;
        die();
    }

    public function badRequest($message = '')
    {
        if ($message == '')
            $message = 'Bad request';

        $this->title = '400 Bad Request';
        $__render__ = $this->errorLayout;
        require $this->layout;
        die();
    }

    public function forbidden($message = '')
    {
        if ($message == '')
            $message = 'Forbidden';

        $this->title = '403 Forbidden';
        $__render__ = $this->errorLayout;
        require $this->layout;
        die();
    }
}
