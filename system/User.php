<?php 
namespace system;

class User {

    public $admins = [];

    protected $session;
    
    public function __construct($options)
    {
        if (isset($options['admins']))
            $this->admins = $options['admins'];
        $this->session = new Session();
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

    public function login($id, $username)
    {
        $this->session->set('user', [
            'id' => $id,
            'username' => $username,
        ]);
        return true;
    }

    public function logout()
    {
        return $this->session->clear();
    }

    public function isLoggedIn()
    {
        return $this->session->get('user') ? true : false;
    }

    public function isAdmin()
    {
        return $this->isLoggedIn() && in_array($this->session->get('user[username]'), $this->admins);
    }

    public function getId()
    {
        if ($this->isLoggedIn())
            return $this->session->get('user[id]');
        return false;
    }

    public function getUsername()
    {
        if ($this->isLoggedIn())
            return $this->session->get('user[username]');
    }

    public function hashPassword($pass)
    {
        return password_hash($pass, PASSWORD_DEFAULT, [
            'cost' => 13,
            'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
        ]);
    }

    public function verifyPassword($pass, $hash)
    {
        return password_verify($pass, $hash);
    }
}