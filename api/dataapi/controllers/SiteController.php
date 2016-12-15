<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;

class SiteController extends Controller
{

    public function actionIndex()
    {

       return $this->render('index');
    }
    public function actionTest()
    {

        return $this->render('test');
    }
    public function actionUpload()
    {

        return $this->render('upload');
    }
}
?>