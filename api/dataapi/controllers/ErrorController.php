<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class ErrorController extends Controller
{

    public $layout="error";
    
    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
}
