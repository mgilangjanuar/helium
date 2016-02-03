<?php
namespace system;

use Valitron\Validator;

class BaseModel 
{

    public $scenarios;

    public function __construct($options = [])
    {
        if (isset($options['scenarios'])) 
            $this->scenarios = $options['scenarios'];
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

    public function __set($key, $value)
    {
        $attribute = 'set' . $key;
        if (method_exists($this, $attribute)) {
            $this->$attribute($value);
        } else {
            throw new \Exception("Can't set $key");
        }
    }

    public static function className()
    {
        return get_called_class();
    }

    public static function realClassName()
    {
        $className = explode('\\', static::className());
        return end($className);
    }

    public function rules()
    {
        return [];
    }

    public function load($values)
    {
        if ($values == null) return false;

        foreach ($values as $key => $value) {
            if ( in_array($key, array_keys(get_object_vars($this))) )
                $this->$key = $value;
        }
        return true;
    }

    public function validate($datas = [])
    {
        foreach (get_class_methods($this) as $funcRule) {
            if ( stripos($funcRule, 'rule') === 0 && $funcRule != 'rules') {
                $args = $this->$funcRule();
                array_unshift($args, lcfirst(str_replace('rule', '', $funcRule)));
                call_user_func_array('\Valitron\Validator::addRule', $args);
            }
        }

        if ($datas == null)
            $datas = get_object_vars($this);

        $validator = new Validator($datas);
        foreach ($this->rules() as $args) {
            call_user_func_array([$validator, 'rule'], $args);
        }

        if ($validator->validate()) return null;
        
        return $validator->errors();
    }

    public function translateFromJson($json)
    {
        foreach (json_decode($json, true) as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }

    public function translateToJson()
    {
        return json_encode($this);
    }

}
