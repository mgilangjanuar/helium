<?php
namespace system;

use Valitron\Validator;

class BaseModel {

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

    public function rules()
    {
        return [];
    }

    public function validate($datas = [])
    {
        foreach (get_class_methods($this) as $funcRule) {
            if (substr($funcRule, 0, 4) == 'rule' && $funcRule != 'rules') {
                call_user_func_array('\Valitron\Validator::addRule', $this->$funcRule());
            }
        }

        if ($datas == null)
            $datas = get_object_vars($this);

        $validator = new Validator($datas);
        foreach ($this->rules() as $args) {
            call_user_func_array([$validator, 'rule'], $args);
        }
        if ($validator->validate()) {
            return true;
        }
        return $validator->errors();
    }

}
