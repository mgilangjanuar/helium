<?php
namespace system;

class Route {

    public $url;
    public $routes;
    public $roles;
    public $defaultController;
    public $defaultFunction;
    public $runFunction;

    public function __construct($url, $config = [])
    {
        $this->url               = stripos($url, '?') ? substr($url, 0, stripos($url, '?')) : $url;
        $this->defaultController = isset($config['defaultController']) ? strtolower($config['defaultController']) : 'site';
        $this->defaultFunction   = isset($config['defaultFunction']) ? strtolower($config['defaultFunction']) : 'index';
        $this->runFunction       = isset($config['runFunction']) ? strtolower($config['runFunction']) : 'action';
        $this->routes            = isset($config['routes']) ? $config['routes'] : [];
        $this->roles = [
            'guest' => isset($config['roles']) && isset($config['roles']['guest']) ? $config['roles']['guest'] : '#',
            'user' => isset($config['roles']) && isset($config['roles']['user']) ? $config['roles']['user'] : '@',
            'admin' => isset($config['roles']) && isset($config['roles']['admin']) ? $config['roles']['admin'] : 'admin'
        ];

        $this->run();
    }

    public function run()
    {
        $class = '\\app\\controllers\\' . $this->activeController() . 'controller';
        $func  = $this->runFunction . $this->activeFunction();
        $args  = explode('/', trim( str_replace($this->activeRoute(), '', $this->url), '/' ));

        if ($this->validate($class, $func) === true)
            return call_user_func_array( [new $class, $func], $args );

        // oops, something wrong! :o
        return $this->badRequestException();
    }

    public function addRoute($route, $function)
    {
        $this->routes[$route] = $function;
    }

    public function notFoundException()
    {
        return (new BaseController)->notFound();
    }

    public function forbiddenException()
    {
        return (new BaseController)->forbidden();
    }

    public function badRequestException()
    {
        return (new BaseController)->badRequest();
    }

    public function activeController()
    {
        foreach ($this->routes as $route => $method) {
            if (stripos($this->url, $route) === 0) {
                $className = explode('\\', strtolower( explode(':', $method)[0] ) );
                return str_replace( 'controller', '', end( $className ) );
            }
        }

        $segments = explode('/', trim($this->url, '/'));
        if (! isset($segments[0]) || $this->url == '/')
            $segments[0] = $this->defaultController;

        return strtolower( str_replace('-', '', $segments[0]) );
    }

    public function activeFunction()
    {
        foreach ($this->routes as $route => $method) {
            if (stripos($this->url, $route) === 0) {
                $funcName = strtolower( explode(':', $method)[1] );
                return str_replace( $this->runFunction, '', $funcName );
            }
        }

        $segments = explode('/', trim($this->url, '/'));
        if (! isset($segments[1]) )
            $segments[1] = $this->defaultFunction;

        return strtolower( str_replace('-', '', $segments[1]) );
    }

    public function activeRoute()
    {
        foreach ($this->routes as $route => $method) {
            if (stripos($this->url, $route) === 0) {
                return $route;
            }
        }
        
        $segments = explode('/', trim($this->url, '/'));
        $result = '/';
        if (isset($segments[0]))
            $result .= $segments[0];
        if (isset($segments[1]))
            $result .= '/' . $segments[1];
        return $result;
    }

    private function validate($class, $func)
    {
        // handle if $class not exist or $class doesnt have $func
        if (! class_exists($class) || ! method_exists(new $class, $func))
            return $this->notFoundException();

        // colect roles in rules that controller
        $roles = [];
        if ((new $class)->rules()['accessControl'] != null ) {
            foreach ((new $class)->rules()['accessControl'] as $key => $value) {
                if (in_array($this->activeFunction(), $value))
                    $roles[] = $key;
            }
        }

        // check roles
        $validate = false;
        if (($roles == null) ||
            (App::$user->isLoggedIn() && in_array($this->roles['user'], $roles)) ||
            (! App::$user->isLoggedIn() && in_array($this->roles['guest'], $roles)) ||
            (App::$user->isLoggedIn() && App::$user->isAdmin() && in_array($this->roles['admin'], $roles)))
            $validate = true;

        // throw to forbidden page if user not in roles
        if (! $validate)
            return $this->forbiddenException();

        // if success
        return true;
    }

}