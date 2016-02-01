<?php
namespace app\models;

use system\BaseModel;

class Example extends BaseModel {

    public function getDatas()
    {
        return [
            'welcome' => 'welcome',
            'description' => 'This is Less Framework. Nothing special.'
        ];
    }
    
}