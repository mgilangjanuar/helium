<?php 
namespace system;

class Url {
    public function path()
    {
        $path = pathinfo($_SERVER['PHP_SELF'])['dirname'];
        return $path == '/' ? '' : $path;
    }

    public function activeUrl()
    {
        return $_SERVER["PATH_INFO"];
    }

    public function baseUrl()
    {
        return 'http://' . $_SERVER['HTTP_HOST'] . $this->path();
    }

    public function urlTo($url = '')
    {
        return $this->baseUrl() . $url;
    }

    public function uriSegment($value = 0)
    {
        return explode("/", trim($_SERVER["REQUEST_URI"], "/"))[$value];
    }

    public function redirect($url)
    {
        if (is_string($url)) {
            header("Location: " . $url);
        } elseif (is_array($url)) {
            header("Location: " . $this->baseUrl() . $url[0]);
        }
        die();
    }
}