<?php 
namespace system;

class User
{

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
        App::$session->set('_user', $model->_cols);
        return true;
    }

    public function logout()
    {
        return App::$session->clear('_user');
    }

    public function isLoggedIn()
    {
        return App::$session->get('_user') ? true : false;
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
        return App::$session->get('_user');
    }

}