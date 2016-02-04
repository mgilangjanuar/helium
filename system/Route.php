<?php
namespace system;

class Route
{

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
    }

    public function run()
    {
        $activeReal = $this->getActiveReal();
        $class = $activeReal['class'];
        $func  = $activeReal['func'];
        $args  = $activeReal['args'];

        if ($this->validate($class, $func, $args) === true)
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
        $className = explode('\\', strtolower( $this->getActiveReal()['class'] ) );
        return str_replace( 'controller', '', end( $className ) );
    }

    public function activeFunction()
    {
        return strtolower( str_replace( $this->runFunction, '', $this->getActiveReal()['func'] ) );
    }

    public function activeRoute()
    {
        return $this->getActiveReal()['route'];
    }

    private function getActiveReal()
    {
        foreach ($this->routes as $route => $method) {
            if (stripos($this->url, $route) === 0) {
                $class = explode(':', $method)[0];
                $func = explode(':', $method)[1];
                $args = array_filter( explode( '/', trim( str_replace($route, '', $this->url) ) ) );
                if (count((new \ReflectionMethod($class, $func))->getParameters()) == count($args))
                    return [
                        'route' => $route,
                        'class' => $class,
                        'func' => $func,
                        'args' => $args
                    ];
            }
        }

        $segments = array_filter( explode('/', trim($this->url, '/')) );
        if (! isset($segments[0]))
            $segments[0] = $this->defaultController;
        if (! isset($segments[1]))
            $segments[1] = $this->defaultFunction;
        return [
            'route' => '/' . $segments[0] . '/' . $segments[1],
            'class' => '\\app\\controllers\\' . str_replace('-', '', $segments[0]) . 'controller',
            'func' => $this->runFunction . str_replace('-', '', $segments[1]),
            'args' => array_slice($segments, 2)
        ];
    }

    private function validate($class, $func, $args)
    {
        // handle if $class not exist or $class doesnt have $func or number of $args not equals
        if (! class_exists($class) || ! method_exists(new $class, $func) || 
                count((new \ReflectionMethod($class, $func))->getParameters()) != count($args))
            return $this->notFoundException();

        // check permissions
        $rules = (new $class)->rules();
        if (isset($rules['accessControl']) && AccessControl::validate($rules['accessControl']) == false) {
            return $this->forbiddenException();
        }

        return true;
    }

}