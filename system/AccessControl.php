<?php
namespace system;

class AccessControl
{

    public static function validate($controls)
    {
        foreach ($controls as $control) {
            if ((is_string($control['route']) && $control['route'] == static::getRoute()) ||
                    (is_array($control['route']) && in_array(static::getRoute(), $control['route']))) {
                if (is_callable($control['callback'])) {
                    return call_user_func($control['callback']);
                } else {
                    return static::getCallbackConst($control['callback']);
                }
            }
        }
        return true;
    }

    private static function getRoute()
    {
        $route = str_replace(App::$route->runFunction, '', App::$route->activeFunction());
        return App::$helper->camelToDashed($route);
    }

    public static function getCallbackConst($value)
    {
        if ($value == '@') {
            return App::$user->isLoggedIn();
        } elseif ($value == '#') {
            return !App::$user->isLoggedIn(); 
        }
        return false;
    }

}