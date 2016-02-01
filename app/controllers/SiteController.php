<?php 
namespace app\controllers;

use system\BaseController;
use app\models\Example;

class SiteController extends BaseController {

    public function actionIndex()
    {
        return $this->render('site/index', [
            'model' => new Example
        ]);
    }
    
}