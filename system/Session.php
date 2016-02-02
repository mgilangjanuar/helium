<?php 
namespace system;

class Session
{

    public function get($data = null)
    {
        if ($data) {
            $datas = explode('[', str_replace(']', '', $data));
            if (!isset($_SESSION[$datas[0]])) return null;
            $result = $_SESSION[$datas[0]];
            foreach ($datas as $i => $data) {
                if ($i > 0)
                    $result = @$result[$data];
            }
            return $result;
        }
        return $_SESSION;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function clear($key = null)
    {
        if ($key) {
            unset($_SESSION[$key]);
            return true;
        }
        session_destroy();
        return true;
    }

    public function isExist($key)
    {
        return isset($_SESSION[$key]);
    }
    
}