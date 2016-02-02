<?php 
namespace app\controllers;

use system\App;
use system\BaseController;
use app\models\Example;

class SiteController extends BaseController {

    public function actionIndex()
    {
        $model = new Example;
        if (App::$request->post('Example'))
            $model->load(App::$request->post('Example'));
        return $this->render('site/index', [
            'model' => $model
        ]);
    }

    public function actionValidateForm()
    {
        echo App::$helper->formValidation(App::$request->post());
        die();
    }
}