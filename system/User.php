<?php 
namespace system;

class User
{

    public $model;
    
    public function __construct($options = [])
    {
        $this->model = isset($options['model']) ? $options['model'] : null;
    }

    public function __get($key)
    {
        $attribute = 'get' . $key;
        if (method_exists($this, $attribute)) {
            return $this->$attribute();
        } else {
            throw new \Exception("Can't get $key");
        }
    }

    public function login($model)
    {
        App::$session->set('user', serialize($model));
        return true;
    }

    public function logout()
    {
        return App::$session->clear('user');
    }

    public function isLoggedIn()
    {
        return App::$session->get('user') ? true : false;
    }

    public function setPassword($pass)
    {
        return password_hash($pass, PASSWORD_DEFAULT, [
            'cost' => 13,
            'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
        ]);
    }

    public function validatePassword($pass, $hash)
    {
        return password_verify($pass, $hash);
    }
    
    public function getIdentity()
    {
        return unserialize(App::$session->get('user'));
    }

}