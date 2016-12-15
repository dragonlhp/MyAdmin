<?php
/**
 *这是一个处理优惠券的管理文件
 *
 *
 * @author      libin<hansen.li@silksoftware.com>
 * @version     1.0
 * @since       1.0
 */
namespace app\modules\coupon\controllers;


use app\models\BsCoupon;
use app\models\BsMemberService;
use app\services\common\DumpCode;
use Yii;
use app\exts\QrckController;
use yii\filters\VerbFilter;
use app\services\common\StateCode;
use common\utils\UtilFilter;

use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;


class CouponController extends QrckController
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
            ],
        ];

        return $behaviors;
    }

    /**
     *
     * 获取优惠券的数据列表
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionIndex()
    {
        $req_company = Yii::$app->request->get("req_company", '');
        $service_id = Yii::$app->request->get("service_id", '');
        $page_no = Yii::$app->request->get("page_no", 1);
        $page_size = Yii::$app->request->get("page_size", Yii::$app->params['default']['page_size']);
        $model = BsCoupon::find()->where('status<9');

        if (!empty($req_company)) {
            $req_company = UtilFilter::addslashesStr($req_company);
            $model->andWhere("req_company LIKE '%$req_company%'");
        }
        if (!empty($service_id)) {
            $service_id = UtilFilter::addslashesStr($service_id);
            $model->andWhere("service_id LIKE '%$service_id%'");
        }

        $total = $model->count();
        $offset = ($page_no - 1) * $page_size;
        $data = $model->orderBy('id DESC')->offset($offset)->limit($page_size)->asArray()->all();

        $res = ['total' => $total, 'rows' => $data, 'page_size' => $page_size, 'page_no' => $page_no];
        return $res;
    }

    /**
     *
     * 获取优惠券的待审核数据列表
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionClist()
    {
        
        $page_no = Yii::$app->request->get("page_no", 1);
        $page_size = Yii::$app->request->get("page_size", Yii::$app->params['default']['page_size']);
        $model = BsCoupon::find()->where('status = 1');

        $total = $model->count();
        $offset = ($page_no - 1) * $page_size;
        $data = $model->orderBy('id DESC')->offset($offset)->limit($page_size)->asArray()->all();

        $res = ['total' => $total, 'rows' => $data, 'page_size' => $page_size, 'page_no' => $page_no];
        return $res;
    }

    /**
     *
     * 获取单个优惠券的数据
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionView()
    {
        $id = Yii::$app->request->get("id", 0);
        $data = BsCoupon::findOne($id);
        if ($data) {
            return $data;
        } else {
            throw new BadRequestHttpException('请求数据错误。');
        }
    }

    /**
     *
     * 创建优惠券数据
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionCreate()
    {
        //var_dump(Yii::$app->getRequest()->getBodyParams());exit;
        $model = new BsCoupon();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        if ($model->save()) {
            return $model;
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('创建数据失败。');
        }
        return $model;
    }

    /**
     *
     * 更新优惠券数据
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionUpdate()
    {
        $id = Yii::$app->request->get("id", 0);

        $model = BsCoupon::findOne($id);
        if ($model) {
            $model->load(Yii::$app->getRequest()->getBodyParams(), '');
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
     * 获取分类Tree
     * @return array
     */
    public function actionGetcategory()
    {

        $data = CmsCategory::find()->select('id,pid,name as text')->asArray()->all();


        return Cmscommon::Getcat($data);


    }

    /**
     *
     * 修改优惠券数据的各种状态  ，删除，发布，取消发布等。
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
                $model = BsCoupon::findOne($i);
                $model->setattribute("status", $status);
                $model->save();
            }
            return ['code' => StateCode::SUCCESS];
        }
    }

    /*
     * 获取服务机构名称
     */
    public function actionGetservice()
    {
        $id = Yii::$app->request->get("service_id", 0);
        $data = BsMemberService::findOne($id);
        if ($data) {
            return $data;
        } else {
            throw new BadRequestHttpException('请求数据错误。');
        }
    }

    /*
     * 获取服务机构名称
     */
    public function actionGetserviceall()
    {
        $data = BsMemberService::find()->select('id,name as text')->asArray()->all();
        if ($data) {
            return $data;
        } else {
            throw new BadRequestHttpException('请求数据错误。');
        }
    }
}