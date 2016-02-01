<?php
namespace system;

class App {

    public static $db;

    public static $assets;

    public static $user;

    public static $url;

    public static $request;

    public static $session;

    public static $helper;

    public static $params;

    public static function activeController()
    {
        return explode("/", str_replace(static::$url->path(), '', $_SERVER['REQUEST_URI']))[0];
    }

    public static function activeFunction()
    {
        return explode("/", str_replace(static::$url->path(), '', $_SERVER['REQUEST_URI']))[1];
    }

    public static function run($config)
    {
        // error_reporting(0);

        /**
         * Initial Configuration
         */

        if (isset($config['db'])) {
            if (isset($config['db']['username'])) {
                static::$db = new DB($config['db']['dsn'], $config['db']['username'], $config['db']['password']);
            } else {
                static::$db = new DB($config['db']['dsn']);
            }
        }

        static::$assets = new Assets(isset($config['assets']) ? $config['assets'] : []);

        static::$user = new User(isset($config['user']) ? $config['user'] : []);

        static::$url = new Url();

        static::$request = new Request();

        static::$session = new Session();

        static::$helper = new Helper();

        static::$params = $config['params'];

        

        /**
         * Route Management
         */ 

        $options            = isset($config['site']) ?  $config['site'] : [];
        $defaultController  = isset($options['defaultController']) ? $options['defaultController'] : 'Site';
        $runFunction        = isset($options['runFunction']) ? $options['runFunction'] : 'action';
        $defaultFunction    = isset($options['defaultFunction']) ? $options['defaultFunction'] : 'index';

        $url = str_replace(static::$url->path(), '', $_SERVER['REQUEST_URI']);

        // build url segments
        if (stripos($url, '?'))
            $url = substr($url, 0, stripos($url, '?'));
        $urlSegments = explode("/", trim($url, "/"));

        // default controller
        if (!isset($urlSegments[0]) || $urlSegments[0] == '')
            $urlSegments[0] = $defaultController;
        $class = '\app\controllers\\' . $urlSegments[0] . 'Controller';

        // function to run
        $func = $runFunction;
        if (! isset($urlSegments[1])) {
            $func .= $defaultFunction;
        } else {
            $func .= str_replace('-', '', $urlSegments[1]);
        }

        // handle if $class doesnt have $func
        if (! class_exists($class) || ! method_exists(new $class, $func))
            return (new BaseController)->notFound();

        // colect roles in rules that controller
        $roles = [];
        if ((new $class)->rules()['accessControl'] != null ) {
            foreach ((new $class)->rules()['accessControl'] as $key => $value) {
                if (in_array(substr($func, 6), $value))
                    $roles[] = $key;
            }
        }

        // check roles
        $validate = false;
        if ($roles == null) {
            $validate = true;
        } elseif (App::$user->isLoggedIn() && in_array('@', $roles)) {
            $validate = true;
        } elseif (!App::$user->isLoggedIn() && in_array('#', $roles)) {
            $validate = true;
        } elseif (App::$user->isLoggedIn() && App::$user->isAdmin() && in_array('admin', $roles)) {
            $validate = true;
        }

        // throw to forbidden page if user not in roles
        if (!$validate)
            return (new $class)->forbidden();

        // finnaly!
        return call_user_func_array([new $class, $func], array_slice($urlSegments, 2));
    }
}
