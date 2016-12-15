<?php
/**
 * 开发工具: PhpStorm.
 * 作   者: mybook-lhp
 * 日   期: 2016年11月17日
 * 功能说明: 会员管理
 */

namespace app\modules\member\controllers;


use app\models\BsMember;
use app\models\BsMemberIncubator;
use app\models\BsMemberService;
use app\models\BsMenu;
use app\services\common\Cmscommon;
use app\services\common\DumpCode;
use app\services\common\StateCode;
use common\services\BdataServices;
use common\utils\UtilEncryption;
use common\utils\UtilFilter;
use Yii;
use app\exts\QrckController;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

class MemberController extends QrckController
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

    /**
     *
     * 获取会员数据列表
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionIndex()
    {
        $page_no = Yii::$app->request->get("page", 1);
        $page_size = Yii::$app->request->get("rows", Yii::$app->params['default']['page_size']);
        $model = BsMember::find()->where('status<9');

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

        //驱除密码字段
        $datas = [];
        foreach ($data as $key => $item) {
            unset($item['pwd']);
            $datas[$key] = $item;
        }

        $res = ['total' => $total, 'rows' => $datas];
        return $res;
    }

    /**
     *
     * 获取单个会员数据数据
     *
     * @access public
     * @since 1.0
     * @return null|static
     * @throws BadRequestHttpException
     */
    public function actionView()
    {
        $id = Yii::$app->request->get("id", 0);
        $data = BsMember::findOne($id)->toArray();
        if ($data) {
            unset($data['pwd']);
            return $data;
        } else {
            throw new BadRequestHttpException('请求数据错误。');
        }
    }

    /**
     *
     * 更新会员数据
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
        $ArrData = Yii::$app->getRequest()->getBodyParams();

        unset($ArrData['login_name']);
        unset($ArrData['mtype']);
        $model = BsMember::findOne($id);
        if ($model) {
            $model->setAttributes($ArrData, false);
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
     * 修改会员数据的各种状态  启用,禁用，保留，删除等。
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
                $model = BsMember::findOne($i);
                $model->setattribute("status", $status);
                $model->save();
            }
            return ['code' => StateCode::SUCCESS];
        }
    }

    //更新权限
    public function actionAccess()
    {

        $parm = Yii::$app->getRequest()->getBodyParams();

        $ids = isset($parm['ids']) ? $parm['ids'] : "";
        $access = isset($parm['access']) ? $parm['access'] : "";


        if (empty($ids) || empty($access)) {
            throw new BadRequestHttpException('请求数据错误。', StateCode::REQUEST_ERROR);
        } else {
            $ids = explode(",", $ids);
            foreach ($ids as $i) {
                $model = BsMember::findOne($i);
                $model->setattribute("access", $access);
                $model->save();
            }
            return ['code' => StateCode::SUCCESS];
        }
    }


    //取出权限列表树
    public function actionGetbsmenu()
    {
        $access = explode(',', Yii::$app->request->get("access", ""));


        $data = BsMenu::find()->select('id,parent_id as pid,name as text,des')->asArray()->all();
        $datas = [];
        foreach ($data as $key => $item) {
            $datas[$key] = $item;
            if (in_array($item['id'], $access)) {
                $datas[$key]['checked'] = 'true';
            }
            if ($item['des'] !== null) {
                $datas[$key]['text'] = $item['text'] . "({$item['des']})";
            } else {
                $datas[$key]['text'] = $item['text'];
            }

        }

        return Cmscommon::Getcat($datas);
    }

    //获取行业列表
    public function actionGetbsbdatatype()
    {
        $type = Yii::$app->request->get("type", 0);
        $get = new BdataServices();
        $datas = $get->getDataByType($type);
        $ret = [];
        foreach ($datas as $key => $data) {
            $ret[$key]['id'] = $data['id'];
            $ret[$key]['text'] = $data['name'];
        }

        return $ret;
    }


    //获取孵化器
    public function actionGetincubator()
    {
        $model = BsMemberIncubator::find()->select('id,name as text ,member_id')->asArray()->all();
        $arr = [];
        foreach ($model as $key => $item) {
            $arr[$key]['id'] = $item['member_id'];
            $arr[$key]['text'] = $item['text'];
        }
        return $arr;
    }

    //获取孵化器用户名
    public function actionGetincubatormember()
    {
        $id = Yii::$app->request->get("id", 0);
        $model = BsMember::find()->select('id,login_name')->where('id = :id', [':id' => $id])->asArray()->all();
        return $model[0]['login_name'];
    }

    //管理员重置密码
    public function actionRepwd()
    {
        $id = Yii::$app->request->get("id", 0);

        $model = BsMember::findOne($id);

        if ($model) {
            $parm = Yii::$app->getRequest()->getBodyParams();

            if (isset($parm['pwd']) && !empty($parm['pwd'])) {
                $parm['pwd'] = UtilEncryption::encryptMD5($parm['pwd'], Yii::$app->params['secret']['pwd']);
            } else {
                unset($parm['pwd']);
            }

            if (isset($parm['id'])) {
                unset($parm['id']);
            }

            if (isset($parm['role_ids'])) {
                if (is_array($parm['role_ids'])) {
                    $parm['role_ids'] = implode(",", $parm['role_ids']);
                } else {
                    $parm['role_ids'] = $parm['role_ids'];
                }
            }

            $model->setattribute('pwd', $parm['pwd']);

            if ($model->save()) {
                unset($model['pwd']);
                return $model;
            } elseif (!$model->hasErrors()) {
                throw new ServerErrorHttpException('更新数据失败。');
            }

        } else {
            throw new BadRequestHttpException('请求数据错误。');
        }
    }


}