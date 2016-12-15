<?php
/**
 * 开发工具: PhpStorm.
 * 作   者: mybook-lhp
 * 日   期: 2016年11月17日
 * 功能说明: 会员管理
 */

namespace app\modules\member\controllers;


use app\models\BsMember;
use app\models\BsMemberPerson;

use app\models\BsMenu;
use Yii;
use app\exts\QrckController;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

class MemberpersonController extends QrckController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['GET'],
                'view' => ['GET'],
                'create' => ['POST'],
                'update' => ['PUT'],
                'status' => ['DELETE'],
            ],
        ];
        return $behaviors;
    }

    //通过ID获取个人数据
    public function actionGet()
    {
        $id = Yii::$app->request->get("member_id", 0);

        $model = BsMemberPerson::find()->where("member_id = $id")->all();

        if ($model) {
            return $model;
        }
    }


    //更新个人数据
    public function actionSave()
    {
        $id = Yii::$app->request->get("id", 0);
        $modelone = BsMemberPerson::find()->where("member_id = $id")->asArray()->all();


        if ($modelone) {
            return $this->update($modelone[0]['id'], Yii::$app->getRequest()->getBodyParams());

        } else {

            return $this->Create($id, Yii::$app->getRequest()->getBodyParams());

        }

    }

    private function update($id, $data)
    {
        $model = BsMemberPerson::findOne($id);
        $model->setAttributes($data, false);
        if ($model->save()) {
            return $model;

        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('更新数据失败。');
        }

    }

    private function Create($id, $data)
    {
        $models = new BsMemberPerson();
        $models->setAttributes($data, false);
        $models->setAttribute('member_id', $id);
        if ($models->save()) {
            return $models;
        } elseif (!$models->hasErrors()) {
            throw new ServerErrorHttpException('创建数据失败。');
        }
        return $models;
    }
}