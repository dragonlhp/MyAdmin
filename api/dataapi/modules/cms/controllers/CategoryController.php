<?php

namespace app\modules\cms\controllers;

use app\exts\QrckController;
use app\models\CmsArticle;
use app\models\CmsCategory;
use app\services\common\Cmscommon;
use app\services\common\DumpCode;
use app\services\common\StateCode;
use common\utils\UtilFilter;
use Yii;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;


class CategoryController extends QrckController
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
                'delete' => ['DELETE'],
            ],
        ];
        return $behaviors;
    }

    /**
     * 获取Category的数据列表
     * @access public
     * @sinc 1.0
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionIndex()
    {

        $model = CmsCategory::find();
        $total = $model->count();
        $data = $model->
        select('id,pid,name as text, lan,module,url, showtype,descriptions,created,updated')
            ->orderBy('id asc')->asArray()->all();

        //默认全部折叠
        $ifdata = [];
        foreach ($data as $value) {
            $ifdata[$value['id']] = $value['pid'];
        }
        $datas = [];
        foreach ($data as $key => $item) {
            $datas[$key] = $item;
            if (in_array($item['id'], $ifdata)) {
                $datas[$key]['state'] = 'closed';
            }

        }
        //编码输出
        $retdata = Cmscommon::Getcat($datas);
        $res = ['total' => $total, 'rows' => $retdata];
        return $res;
    }

    /**
     * 获取Category单个数据
     * @return null|static
     */
    public function actionView()
    {

        $id = Yii::$app->request->get('id', 0);
        $data = CmsCategory::findOne($id);
        if ($data) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(StateCode::SUCCESS);
        }
        return $data;
    }

    /**
     * 添加Category数据
     * @return Category
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        //获取父级语言
        $loaddata = Yii::$app->getRequest()->getBodyParams();
        $lan = CmsCategory::findOne($loaddata['pid'])->toArray();

        //创建
        $model = new CmsCategory();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $model->setattribute("lan", $lan['lan']);
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(StateCode::SUCCESS);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('添加失败!');

        }

        return $model;
    }

    /**
     * 修改Category数据
     * @return null|static
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate()
    {

        //获取父级语言
        $loaddata = Yii::$app->getRequest()->getBodyParams();
        $lan = CmsCategory::findOne($loaddata['pid'])->toArray();

        $id = Yii::$app->request->get("id", 0);
        $model = CmsCategory::findOne($id);
        if ($model) {
            $model->load(Yii::$app->getRequest()->getBodyParams(), '');
            $model->setattribute("lan", $lan['lan']);
            if ($model->save()) {
                return $model;
            } elseif (!$model->hasErrors()) {
                throw new ServerErrorHttpException('更新数据失败。');
            }
        } else {
            throw new BadRequestHttpException('更新数据失败。');
        }


    }


    /**
     * 删除Category数据
     * @return null|static
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     */
    public function actionDelete()
    {
        $parm = Yii::$app->getRequest()->getBodyParams();
        $ids = explode(",", isset($parm['ids']) ? $parm['ids'] : "");
        if (empty($ids)) {
            throw new BadRequestHttpException('请求数据错误。', StateCode::REQUEST_ERROR);
        } else {
            $errflag = false;

            foreach ($ids as $i) {
                $model = CmsCategory::findOne($i);
                $count = CmsCategory::find()->where(['pid' => $i])->count();  //判断分类下是否还有分类
                $countArt = CmsArticle::find()->where(['category_id' => $i])->count(); //判断分类下是否还有文章
                if ($count > 0) {
                    $errflag = true;
                } elseif ($countArt > 0) {
                    $errflag = true;
                } else {
                    $model->delete();
                }
            }
            if (!$errflag) {
                return ['code' => StateCode::SUCCESS];//删除成功
            } else {
                return ['code' => StateCode::SOME_ERROR];//部分数据操作失败。
            }

        }
    }


    /**
     * 获取分类Tree
     * @return array
     */
    public function actionGetcategory()
    {

        $data = CmsCategory::find()->select('id,pid,name as text')->asArray()->all();


        return Cmscommon::Getcat($data);


    }


    /**
     * 获取分类二维数组
     * @return array
     */
    public function actionArrcategory()
    {
//        $model->setattribute("status", $showtype);
        $data = CmsCategory::find()->select('id,pid,name as text')->asArray()->all();
        $retdata = array();
        foreach ($data as $item) {
            $retdata[$item['id']] = $item;
        }
//        DumpCode::P($retdata);
        return $retdata;


    }


    /**
     *
     * 修改Category数据的各种语言  ，中文，英文，韩文等。
     *
     * @access public
     * @since 1.0
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionLan()
    {

        $parm = Yii::$app->getRequest()->getBodyParams();

        $ids = isset($parm['ids']) ? $parm['ids'] : "";
        $lan = isset($parm['lan']) ? $parm['lan'] : "";


        if (empty($ids) || empty($lan)) {
            throw new BadRequestHttpException('请求数据错误。', StateCode::REQUEST_ERROR);
        } else {
            $ids = explode(",", $ids);
            foreach ($ids as $i) {
                $model = CmsCategory::findOne($i);
                $model->setattribute("status", $lan);
                $model->save();
            }
            return ['code' => StateCode::SUCCESS];
        }
    }

    /**
     *
     * 修改Category数据的显示类型。
     * 1：列表显示  2：内容显示（显示分类下的第一篇文章内容） 3：跳转
     * @access public
     * @since 1.0
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionShowtype()
    {

        $parm = Yii::$app->getRequest()->getBodyParams();

        $ids = isset($parm['ids']) ? $parm['ids'] : "";
        $showtype = isset($parm['showtype']) ? $parm['showtype'] : "";


        if (empty($ids) || empty($showtype)) {
            throw new BadRequestHttpException('请求数据错误。', StateCode::REQUEST_ERROR);
        } else {
            $ids = explode(",", $ids);
            foreach ($ids as $i) {
                $model = CmsCategory::findOne($i);
                $model->setattribute("status", $showtype);
                $model->save();
            }
            return ['code' => StateCode::SUCCESS];
        }
    }
}
