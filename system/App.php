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

    public static $route;

    public static function run($config)
    {
        // error_reporting(0);

        if (isset($config['db'])) {
            if (isset($config['db']['username'])) {
                static::$db = new DB($config['db']['dsn'], $config['db']['username'], $config['db']['password']);
            } else {
                static::$db = new DB($config['db']['dsn']);
            }
        }

        static::$assets   = new Assets(isset($config['assets']) ? $config['assets'] : []);

        static::$user     = new User(isset($config['user']) ? $config['user'] : []);

        static::$url      = new Url();

        static::$request  = new Request();

        static::$session  = new Session();

        static::$helper   = new Helper();

        static::$params   = $config['params'];


        $url            = str_replace(static::$url->path(), '', $_SERVER['REQUEST_URI']);
        $options        = isset($config['route']) ?  $config['route'] : [];
        static::$route  = new Route($url, $options);
    }

}
