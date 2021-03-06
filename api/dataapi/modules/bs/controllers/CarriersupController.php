<?php
/**
 * 开发工具: PhpStorm.
 * 作   者: mybook-lhp
 * 日   期: 16/11/8
 * 功能说明: 载体供应管理
 */

namespace app\modules\bs\controllers;

use app\models\BasicUser;
use app\models\BsMember;
use app\models\BsMemberIncubator;
use app\models\BsSdCarrierSup;
use app\services\common\DumpCode;
use app\services\common\StateCode;
use common\utils\UtilFilter;
use Yii;
use app\exts\QrckController;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

class CarriersupController extends QrckController
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
                'getincubator' => ['GET'],
                'getincubatormember' => ['GET'],
            ],
        ];
        return $behaviors;
    }

    /**
     *
     * 获取载体供应数据列表
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionIndex()
    {

        $page_no = Yii::$app->request->get("page", 1);
        $page_size = Yii::$app->request->get("rows", Yii::$app->params['default']['page_size']);
        $model = BsSdCarrierSup::find()->where('status<9');

        $status = Yii::$app->request->get("status", "");
        $keyword = Yii::$app->request->get("keyword", "");
        $keytype = Yii::$app->request->get("keytype", "");
        if (!empty($status)) {
            $status = UtilFilter::addslashesStr($status);
            $model->andWhere(" status = $status");
        }
        //文本搜索模糊查询
        if (!empty($keyword)) {
            $keyword = UtilFilter::addslashesStr($keyword);
            $keytype = UtilFilter::addslashesStr($keytype);
            $keytypes = explode(',', $keytype);
            $Where = '';
            foreach ($keytypes as $k => $keytype) {
                if ($k == 0) {
                    $Where = "    $keytype like '%$keyword%' ";
                } else {
                    $Where .= " or $keytype like '%$keyword%' ";
                }
            }
            $model->andWhere(" $Where ");
        }

        $total = $model->count();
        $offset = ($page_no - 1) * $page_size;
        $data = $model->orderBy('id DESC')->offset($offset)->limit($page_size)->asArray()->all();


        //取出Member中的载体用户;
        $datas = [];
        foreach ($data as $key => $item) {
            if ($item['owner_type'] == 2) {
                $Member = BsMember::findOne($item['member_id']);
                $datas[$key] = $item;
                $datas[$key]['member'] = $Member['login_name'] ? $Member['login_name'] : '管理员';
                $datas[$key]['owner'] = $Member['login_name'] ? $Member['login_name'] : '管理员';
            } else {
                $Member = BsMember::findOne($item['member_id']);
                $datas[$key] = $item;
                $datas[$key]['member'] = $Member['login_name'] ? $Member['login_name'] : '管理员';

                $User = BasicUser::findOne($this->_login_user_id);
                $datas[$key]['owner'] = $User['name'] ? $User['name'] : '管理员';
            }
        }

        foreach ($datas as $k => $val) {
            $Member = BsMemberIncubator::findOne($val['incubator_id']);
            $datas[$k] = $val;
            $datas[$k]['IncubatorName'] = $Member['name'] ? $Member['name'] : '管理员';
        }


        $res = ['total' => $total, 'rows' => $datas];
        return $res;
    }

    /**
     *
     * 获取单个载体供应数据数据
     *
     * @access public
     * @since 1.0
     * @return null|static
     * @throws BadRequestHttpException
     */
    public function actionView()
    {
        $id = Yii::$app->request->get("id", 0);
        $data = BsSdCarrierSup::findOne($id);
        $datas = [];
//        foreach ($data as $key => $item) {
//            $Member = BsMember::findOne($item['member_id']);
//            $datas[$key] = $item;
//            $datas[$key]['member'] = $Member['login_name'];
//        }
//
//        foreach ($datas as $k => $val) {
//            $Member = BsMemberIncubator::findOne($val['incubator_id']);
//            $datas[$k] = $val;
//            $datas[$k]['IncubatorName'] = $Member['name'] ? $Member['name'] : '';
//        }

        if ($data) {
            return $data;
        } else {
            throw new BadRequestHttpException('请求数据错误。');
        }
    }

    /**
     *
     * 创建载体供应数据
     *
     * @access public
     * @since 1.0
     * @return BsSdCarrierSup
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $model = new BsSdCarrierSup();
        $model->setAttributes(Yii::$app->getRequest()->getBodyParams(), false);
        $model->setAttribute('owner_type', '1');
        $model->setAttribute('owner', '111');
        if ($model->save()) {
            return $model;
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('创建数据失败。');
        }
        return $model;
    }

    /**
     *
     * 更新载体供应数据
     *
     * @access public
     * @since 1.0
     * @return null|static
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate()
    {
        $id = Yii::$app->request->get("id", 0);

        $model = BsSdCarrierSup::findOne($id);
        if ($model) {
            $model->setAttributes(Yii::$app->getRequest()->getBodyParams(), false);
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
     *
     * 修改载体供应数据的各种状态  ，删除，发布，取消发布等。
     *
     * @access public
     * @since 1.0
     * @return array
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionStatus()
    {

        $parm = Yii::$app->getRequest()->getBodyParams();

        $ids = isset($parm['ids']) ? $parm['ids'] : "";
        $status = isset($parm['status']) ? $parm['status'] : "";


        if (empty($ids) || empty($status)) {
            throw new BadRequestHttpException('请求数据错误。', StateCode::REQUEST_ERROR);
        } else {
            $ids = explode(",", $ids);
            foreach ($ids as $i) {
                $if = BsSdCarrierSup::findOne($i)->toArray();
                if ($if['status'] < 3) {
                    $model = BsSdCarrierSup::findOne($i);
                    $model->setattribute("status", $status);
                    $model->save();
                }
            }
            return ['code' => StateCode::SUCCESS];
        }
    }


    //获取孵化器
    public function actionGetincubator()
    {
        $model = BsMemberIncubator::find()->select('id,name as text ,member_id')->asArray()->all();
        $arr = [];
        foreach ($model as $key => $item) {
//            $arr[$key]['id'] = $item['member_id'];
            $arr[$key]['id'] = $item['id'];
            $arr[$key]['text'] = $item['text'];
        }
        return $arr;
    }

    //获取孵化器用户名
    public function actionGetincubatormember()
    {
        $id = Yii::$app->request->get("id", 0);
        $model = BsMember::find()->select('id,login_name')->where('id = :id', [':id' => $id])->asArray()->all();
        return $model[0];
    }


}