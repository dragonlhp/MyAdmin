<?php
/**
 *这是一个处理优惠券的管理文件
 *
 *
 * @author      libin<hansen.li@silksoftware.com>
 * @version     1.0
 * @since       1.0
 */
namespace app\modules\isorder\controllers;


use app\models\BsCoupon;
use app\models\BsMemberService;
use app\models\BsSdApply;
use app\models\BsSdApplyAll;
use app\services\common\DumpCode;
use Yii;
use app\exts\QrckController;
use yii\filters\VerbFilter;
use app\services\common\StateCode;
use common\utils\UtilFilter;

use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;


class OrderyesController extends QrckController
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
     * 获取全部的数据列表
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionIndex(){

        $page_no = Yii::$app->request->get("page_no", 1);
        $page_size = Yii::$app->request->get("rows", Yii::$app->params['default']['page_size']);
        $model = BsSdApplyAll::find()->where(['<>','check_status',1]);
        $check_status = Yii::$app->request->get("check_status", "");
        $status = Yii::$app->request->get("status", "");
        $pro_type = Yii::$app->request->get("pro_type", "");
        $keyword = Yii::$app->request->get("keyword", "");
        $keytype = Yii::$app->request->get("keytype", "");
        if (!empty($check_status)) {
            $check_status = UtilFilter::addslashesStr($check_status);
            $model->andWhere(" check_status = $check_status");
        }
        if (!empty($status)) {
            $status = UtilFilter::addslashesStr($status);
            $model->andWhere(" status = $status");
        }
        if (!empty($pro_type)) {
            $pro_type = UtilFilter::addslashesStr($pro_type);
            $model->andWhere(" pro_type = $pro_type");
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
        $data = $model->orderBy('pro_type DESC')->offset($offset)->limit($page_size)->asArray()->all();


        $res = ['total' => $total, 'rows' => $data];
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
        $model = BsSdApply::find()->where('status = 1');

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
        $data = BsSdApplyAll::find()->where(['id'=>$id])->one();
//        $data = BsSdApply::findOne($id);
//        $data = BsSdApplyAll::find($id);
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
        $model = new BsSdApply();
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

        $model = BsSdApply::findOne($id);

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
    public function actionCheckstatus()
    {

        $parm = Yii::$app->getRequest()->getBodyParams();

        $ids = isset($parm['ids']) ? $parm['ids'] : "";
        $check_status = isset($parm['check_status']) ? $parm['check_status'] : "";


        if (empty($ids) || empty($check_status)) {
            throw new BadRequestHttpException('请求数据错误。', StateCode::REQUEST_ERROR);
        } else {
            $ids = explode(",", $ids);

            foreach ($ids as $i) {
                $model = BsSdApply::findOne($i);
                $model->setattribute("check_status", $check_status);
                if($check_status == 3){
                    $model::updateAll(['status'=>3],['id'=>$i]);
                }
                $model->save();
            }
            return ['code' => StateCode::SUCCESS];
        }
    }
    /**
     * @return null|static
     * @throws BadRequestHttpException
     */
    public function actionStatus(){
        $id = Yii::$app->request->post("id", 0);
        $status = Yii::$app->request->post('status',0);

        $model = BsSdApply::findOne($id);

        if ($model) {
            $res = $model::updateAll(['status'=>$status],['id'=>$id]);
            if ($res) {
                return $res;
            } elseif (!$model->hasErrors()) {
                throw new ServerErrorHttpException('更新数据失败。');
            }
        } else {
            throw new BadRequestHttpException('更新数据失败。');
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