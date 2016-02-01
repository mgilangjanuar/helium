<?php
namespace system;

class Form {

    public static function begin($datas = [], $options = [])
    {
        $form = '<form ' . Form::buildOptions($options) . '>';
        $field = '<input name="_validation" type="hidden" value="' . (isset($datas['validation']) ? $datas['validation'] : '') . '">';
        return $form . $field;
    }

    public static function end()
    {
        return '</form>';
    }

    private static function buildOptions($options = [])
    {
        $result = '';
        foreach ($options as $key => $value) {
            $result .= $key  . '="' . $value . '" ';
        }
        return trim($result);
    }

}