<?php
namespace system;

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



    /**
     * Validation Methods
     */

    public function ruleRequired($rule)
    {
        $errors = [];
        if ($this->$rule[0] == null)
            $errors[] = 'This field is required';
        return $errors == null ? [] : $errors;
    }

    public function ruleString($rule)
    {
        $errors = [];
        if (!ctype_alpha($this->$rule[0]))
            $errors[] = 'This field must be string';
        return $errors == null ? [] : $errors;
    }

    public function ruleInteger($rule)
    {
        $errors = [];
        if (!ctype_digit($this->$rule[0]))
            $errors[] = 'This field must be integer';
        return $errors == null ? [] : $errors;
    }

    public function ruleAlphanumeric($rule)
    {
        $errors = [];
        if (!ctype_alnum($this->$rule[0]))
            $errors[] = 'This field must be alphanumeric';
        return $errors == null ? [] : $errors;
    }

    public function ruleMax($rule)
    {
        $errors = [];
        if (strlen($this->$rule[0]) > $rule[1]['max'])
            $errors[] = 'This field must be less than ' . $rule[1]['max'] . ' characters';
        return $errors == null ? [] : $errors;
    }

    public function ruleMin($rule)
    {
        $errors = [];
        if (strlen($this->$rule[0]) < $rule[1]['min'])
            $errors[] = 'This field must be greater than ' . $rule[1]['min'] . ' characters';
        return $errors == null ? [] : $errors;
    }

    public function ruleMaxValue($rule)
    {
        $errors = [];
        if ($this->$rule[0] > $rule[1]['maxvalue'])
            $errors[] = 'This field must be less than ' . $rule[1]['maxvalue'];
        return $errors == null ? [] : $errors;
    }

    public function ruleMinValue($rule)
    {
        $errors = [];
        if ($this->$rule[0] < $rule[1]['minvalue'])
            $errors[] = 'This field must be greater than ' . $rule[1]['minvalue'];
        return $errors == null ? [] : $errors;
    }

    public function ruleEqual($rule)
    {
        $errors = [];
        if ($this->$rule[0] != $rule[1]['equal'])
            $errors[] = 'This field must be ' . $rule[1]['equal'];
        return $errors == null ? [] : $errors;
    }

    public function ruleEmail($rule)
    {
        $errors = [];
        if (filter_var($this->$rule[0], FILTER_VALIDATE_EMAIL) === false)
            $errors[] = 'This field must be email format';
        return $errors == null ? [] : $errors;
    }

    public function ruleUrl($rule)
    {
        $errors = [];
        if (filter_var($this->$rule[0], FILTER_VALIDATE_URL) === false)
            $errors[] = 'This field must be URL format';
        return $errors == null ? [] : $errors;
    }

    public function ruleDate($rule)
    {
        $errors = [];
        if (! strtotime($this->$rule[0]))
            $errors[] = 'This field must be date with format mm/dd/yyyy';
        return $errors == null ? [] : $errors;
    }

    public function validate()
    {   
        $errors = [];

        foreach ($this->rules() as $rule) {
            $errors[$rule[0]] = [];
            foreach ($rule[1] as $key => $data) {
                if (is_string($key)) {
                    $func = 'rule' . $key;
                } else {
                    $func = 'rule' . $data;
                }
                if (method_exists($this, $func))
                    $errors[$rule[0]] = array_merge($errors[$rule[0]], $this->$func($rule));
            }
        }
        return $errors;
    }

    public function isValidate()
    {
        foreach ($this->validate() as $key => $value) {
            if ($value != null) return false;
        }
        return true;
    }
}
