<?php
namespace app\models;

use system\BaseModel;

class Example extends BaseModel {

    public $name;
    public $email;

    public function getDatas()
    {
        return [
            'welcome' => 'welcome',
            'description' => 'This is Less Framework. Nothing special.'
        ];
    }

    public function rules()
    {
        return [
            ['required', ['name', 'email']],
            ['alpha', ['name']],
            ['email', ['email']]
        ];
    }
    
}