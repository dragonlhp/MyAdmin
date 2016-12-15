<?php
/**
 *这是一个处理管理员的管理文件
 *
 *
 * @author      libin<hansen.li@silksoftware.com>
 * @version     1.0
 * @since       1.0
 */
namespace app\modules\db\controllers;

use Yii;
use app\exts\QrckController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

use common\utils\UtilEncryption;
use app\services\common\StateCode;
use app\services\common\Cmscommon;
use app\models\BsGbExpert;
use common\utils\UtilFilter;


class ExpertController extends QrckController
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
                'status' => ['PUT'],
                'menus' => ['GET'],
                'pwd' => ['PUT'],
            ],
        ];
        return $behaviors;
    }

    /**
     *
     * 获取管理员的数据列表
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionIndex()
    {
        $page_no = Yii::$app->request->get("page_no", 1);
        $keyword=Yii::$app->request->get("keyword", "");
        $status=intval(Yii::$app->request->get("status", ""));

        $page_size = Yii::$app->request->get("page_size", Yii::$app->params['default']['page_size']);
        $model = BsGbExpert::find()->where("status < 9 ");
        if($status>0){
            $model=$model->andWhere(['status'=>$status]);
        }
        $keyword=UtilFilter::addslashesStr($keyword);

        if(!empty($keyword)){
            $model=$model->andWhere("(name like '%".$keyword."%' or org_name like '%".$keyword."%' or position like '%".$keyword."%')");
        }
        $total = $model->count();


        $offset = ($page_no - 1) * $page_size;
        $data = $model->orderBy('id DESC')->offset($offset)->limit($page_size)->asArray()->all();

        /*if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $rolename = "";
                if (!empty($value['role_ids'])) {
                    $roles = BasicRole::find()->where("id in (" . $value['role_ids'] . ")")->all();
                    if (count($roles)) {
                        foreach ($roles as $k => $v) {
                            $rolename .= empty($rolename) ? $v->name : "," . $v->name;
                        }
                    }
                }
                $data[$key]['role'] = $rolename;
            }
        }*/

        $res = ['total' => $total, 'rows' => $data, 'page_size' => $page_size, 'page_no' => $page_no];
        return $res;
    }

    /**
     *
     * 获取单个管理员的数据
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionView()
    {
        $id = Yii::$app->request->get("id", 0);
        $data = BsGbExpert::findOne($id)->toArray();
        if ($data) {
            unset($data['pwd']);
            return $data;
        } else {
            throw new BadRequestHttpException('请求数据错误。');
        }

    }

    /**
     *
     * 创建管理员数据
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionCreate()
    {
        $model = new BsGbExpert();
        $parm = Yii::$app->getRequest()->getBodyParams();
        $model->load($parm, '');
        if ($model->save()) {
            return $model;
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('创建数据失败。');
        }
    }

    /**
     *
     * 更新管理员数据
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionUpdate()
    {
        $id = Yii::$app->request->get("id", 0);

        $model = BsGbExpert::findOne($id);

        if ($model) {
            $parm = Yii::$app->getRequest()->getBodyParams();

            $model->load($parm, '');

            if ($model->save()) {
                return $model;
            } elseif (!$model->hasErrors()) {
                throw new ServerErrorHttpException('更新数据失败。');
            }

        } else {
            throw new BadRequestHttpException('请求数据错误。');
        }

    }

    /**
     *
     * 修改管理员数据的各种状态  ，删除，启用，停用等。
     *
     * @access public
     * @since 1.0
     * @return array
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
                $model = BsGbExpert::findOne($i);
                $model->setattribute("status", $status);
                $model->save();
            }
            return ['code' => StateCode::SUCCESS];
        }
    }
}